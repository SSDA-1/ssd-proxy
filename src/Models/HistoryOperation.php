<?php

namespace ssda1\proxies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HistoryOperation extends Model
{
    use HasFactory;
    
    protected $guarded = [];

    /**
     * Получить Пользователя .
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
