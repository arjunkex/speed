<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class NewsletterSubscription extends Model
{
    use SoftDeletes, Notifiable;

    protected $fillable = ['email'];
}
