<?php

namespace ssd\proxies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WithdrawalRequest extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    /**
     * Получить Пользователя .
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
