<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DomainRequest extends Model
{
    use HasFactory;

    const STATUS_PENDING = 0;
    const STATUS_CONNECTED = 1;
    const STATUS_REJECTED = 2;
    const STATUS_REMOVED = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tenant_id',
        'requested_domain',
        'status',
        'modified_by_id',
        'modified_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'id' => 'integer',
        'modified_by_id' => 'integer',
        'modified_at' => 'timestamp',
    ];

    /**
     * @throws \Exception
     */
    static public function getDomain($url): string
    {
        $host = parse_url($url, PHP_URL_HOST);

        if(filter_var($host,FILTER_VALIDATE_IP)) {
            // IP address returned as domain
            throw new \Exception('Not a valid url.');
        }

        $domain_array = explode(".", str_replace('www.', '', $host));
        $count = count($domain_array);
        if( $count>=3 && strlen($domain_array[$count-2])==2 ) {
            // SLD (example.co.uk)
            return implode('.', array_splice($domain_array, $count-3,3));
        } else if( $count>=2 ) {
            // TLD (example.com)
            return implode('.', array_splice($domain_array, $count-2,2));
        }

        throw new \Exception('Not a valid url.');
    }

    public function getStatusHtml()
    {
        $statusBadge = [
            self::STATUS_PENDING => 'badge-info',
            self::STATUS_CONNECTED => 'badge-success',
            self::STATUS_REJECTED => 'badge-danger',
            self::STATUS_REMOVED => 'badge-warning',
        ];

        return '<span class="badge '.$statusBadge[$this->status].'">'.$this->getStatusText().'</span>';
    }

    public function getStatusText(): string
    {
        $statusText = [
            self::STATUS_PENDING => 'Pending',
            self::STATUS_CONNECTED => 'Connected',
            self::STATUS_REJECTED => 'Rejected',
            self::STATUS_REMOVED => 'Removed',
        ];

        return $statusText[$this->status];
    }

    public function tenant(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function modifiedBy(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'modified_by_id');
    }
}
