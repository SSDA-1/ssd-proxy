<?php

namespace ssda1\proxies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TariffSettings extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'days_tariff' => 'array',
        'tariff' => 'array'
    ];
}
