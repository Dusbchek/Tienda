<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class products extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $fillable = [
        'name',
        'slug',
        'img',
        'price',
        'description',
        'is_visible'
    ];

    protected $casts = [
        'is_visible' => 'boolean',
    ];

    public function colors(): BelongsToMany
    {
        return $this->belongsToMany(colors::class, 'product_colors');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(categories::class, 'product_categories');
    }

    public function sizes(): BelongsToMany
    {
        return $this->belongsToMany(sizes::class, 'products_sizes');
    }

    public function measures(): HasMany
    {
        return $this->hasMany(measures::class);
    }


    public function stock(): HasMany
    {
        return $this->hasMany(stock::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(images::class);
    }

    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(orders::class, 'order_products');
    }
}
