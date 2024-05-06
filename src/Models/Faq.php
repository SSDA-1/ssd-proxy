<?php

namespace Ssda1\proxies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    /**
     * Атрибуты, которые можно назначать массово.
     *	
     * @var array
     */
    protected $fillable = [
        'question', 'answer'
    ];
}
