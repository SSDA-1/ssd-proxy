<?php

namespace Ssda1\proxies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Proxy extends Model
{
    use HasFactory;

    protected $guarded = [];

    
    /**
     * Получить Информацию о модеме .
     */
    public function modem()
    {
        return $this->belongsTo(Modem::class);
    }

    /**
     * Получить Пользователя .
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
