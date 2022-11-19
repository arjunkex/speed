<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Stancl\Tenancy\Contracts\TenantWithDatabase;
use Stancl\Tenancy\Database\Concerns\HasDatabase;
use Stancl\Tenancy\Database\Concerns\HasDomains;
use Stancl\Tenancy\Database\Models\Tenant as BaseTenant;

class Tenant extends BaseTenant implements TenantWithDatabase
{
    use HasDatabase, HasDomains, Billable, Notifiable;

    protected $with = [
        'domains',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'on_trial',
        'is_subscribed',
        'photo_url',
        'domain_url',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'plan_ends_at' => 'datetime',
    ];

    public static function getCustomColumns(): array
    {
        return [
            'id',
            'plan_id',
            'plan_ends_at',
            'stripe_id',
            'pm_type',
            'pm_last_four',
            'trial_ends_at',
        ];
    }

    public function plan()
    {
        $plans = tenancy()->central(function () {
            return Plan::orderBy('amount')->first();
        });

        return $this->belongsTo(Plan::class)
            ->withDefault(function () use ($plans) {
                return $plans;
            });
    }

    public function isSubscribed(): Attribute
    {
        return new Attribute(
            get: function () {
                if (! is_null($this->plan_id) || $this->onTrial()) {
                    if (! is_null($this->plan_ends_at)) {
                        return $this->plan_ends_at->gt(now());
                    }

                    return true;
                } else {
                    return false;
                }
            }
        );
    }


    /**
     * Determine if the subscription is within its trial period.
     *
     */
    public function getOnTrialAttribute()
    {
        return $this->onTrial();
    }

    public function photoUrl(): Attribute
    {
        return new Attribute(
            get: fn ($value) => vsprintf(
                'https://www.gravatar.com/avatar/%s.jpg?s=200&d=%s', [
                    md5(strtolower($this->email)), $this->name ?
                        urlencode("https://ui-avatars.com/api/$this->name") : 'mp',
                ]
            ),
        );
    }

    public function domainUrl(): Attribute
    {
        return new Attribute(
            get: fn ($value) => str_contains($this->domain, '.') ?
                request()->getScheme().'://'.$this->domain
                :
                request()->getScheme().'://'.$this->domain.'.'.request()->getHost(),
        );
    }

    public function hasVerifiedEmail(): bool
    {
        return ! is_null($this->email_verified_at);
    }

    public function getEmailForVerification()
    {
        return $this->email;
    }

    public function markEmailAsVerified()
    {
        $this->forceFill([
            'email_verified_at' => $this->freshTimestamp(),
        ])->save();
    }
}
