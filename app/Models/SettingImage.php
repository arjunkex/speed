<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'image',
        'title',
        'description',
        'name',
        'type',
        'image_align_left',
        'points',
        'button_text',
        'button_link',
        'status',
    ];
}
