<?php

namespace Ssda1\proxies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modem extends Model
{
    use HasFactory;

    protected $casts = [
        'users' => 'array',
    ];

    protected $guarded = [];
    
    /**
     * Получить Прокси модема .
     */
    public function server()
    {
        return $this->belongsTo(Server::class);
    }
    
    /**
     * Получить Прокси модема .
     */
    public function getProxyhttporsocsAttribute()
    {
        $hsManyArray = $this->hasMany(Proxy::class)->orderBy('id', 'desc')->get()->toArray();
        return count($hsManyArray);
    }
    
    /**
     * Получить Прокси модема .
     */
    public function getProxycountAttribute()
    {
        $hsManyArray = $this->hasMany(Proxy::class)->orderBy('id', 'desc')->get()->unique('user_id')->toArray();
        return count($hsManyArray);
    }
    
    /**
     * Получить данные о заполнености Модема.
     */
    public function getProxyfullAttribute()
    {
        $hsManyArray = $this->hasMany(Proxy::class)->orderBy('id', 'desc')->get()->unique('user_id')->toArray();
        $countFreeModem = count($hsManyArray);
        $proxyCount = $this->max_users; //hasMany(Proxy::class)->orderBy('id', 'desc')
        if ($countFreeModem == $proxyCount) {
            $statusReturn = 'full';
        }else{
            $statusReturn = $countFreeModem - $proxyCount;
        }
        return $statusReturn;
    }
    
    // /**
    //  * Получить данные о Пользователях в модеме.
    //  */
    // public function getUsersAttribute()
    // {
    //     if (empty($this->users)) {
    //         return '[]'; // Возвращение пустого массива, если поле пустое
    //     }

    //     $userIds = json_decode($this->users, true); // Преобразование JSON-строки в массив


    //     $users = User::whereIn('id', $userIds)->get(); // Получение пользователей по идентификаторам

    //     return $users;
    // }

    /**
     * Получить Прокси модема .
     */
    public function proxys()
    {
        return $this->hasMany(Proxy::class)->orderBy('id', 'desc');
    }
}
