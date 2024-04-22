<?php

namespace ssda1\proxies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubMenu extends Model
{
    use HasFactory;
    
    /**
     * Атрибуты, которые можно назначать массово.
     *	
     * @var array
     */
    protected $fillable = [
        'id_menu', 'name', 'link'
    ];
}