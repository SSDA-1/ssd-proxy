<?php

namespace Ssda1\proxies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Support extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    /**
     * Получить Все сообщения .
     */
    public function AllSupportMassage()
    {
        return $this->hasMany(SupportMassages::class);
    }


    /**
     * Получить последнюю ставку.
     */
    public function lastsuppmassage()
    {
        return $this->hasOne(SupportMassages::class)->latest()->orderBy('id', 'desc');
    }

    /**
     * Получить Первое сообщение.
     */
    public function firstsuppmassage()
    {
        return $this->hasOne(SupportMassages::class)->latest()->orderBy('id', 'asc');
    }

    /**
     * Получить Пользователя обращения .
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
