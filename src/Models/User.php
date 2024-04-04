<?php

namespace ssd\proxies\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'kraken_username',
        'kraken_password',
        'id_kraken',
        'telegram_chat_id',
        'telegram_auth_id',
        'telegram_name'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Получить Историю операций .
     */
    public function historyOperation(): HasMany
    {
        return $this->hasMany(HistoryOperation::class)->orderBy('id', 'desc');
    }

    /**
     * Получить Прокси Пользователя .
     */
    public function proxys(): HasMany
    {
        return $this->hasMany(Proxy::class)->orderBy('id', 'desc');
    }

    /**
     * Получить Пользователя обращения .
     */
    public function support(): HasMany
    {
        return $this->hasMany(Support::class);
    }

    public function templates(): BelongsToMany
    {
        return $this->belongsToMany(Template::class, 'template_user', 'user_id', 'template_id')->withPivot('is_active')->orderBy('is_active','DESC');
    }
}
