<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class shipments extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(orders::class);
    }
}
