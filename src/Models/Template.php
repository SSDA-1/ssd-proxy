<?php

namespace ssd\proxies\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Template extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'description',
        'cost',
        'directory',
        'image',
        'link'
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class,'template_user', 'template_id', 'user_id')->withPivot('is_active')->orderBy('is_active','DESC');
    }
}
