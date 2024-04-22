<?php

namespace ssda1\proxies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $guarded = [];
    

    /**
     * Получить Реферала .
     */
    public function referral()
    {
        return $this->belongsTo(User::class, 'referred_by');
    }

    /**
     * Получить Пользователя .
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
