<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class orderProducts extends Model
{
    use HasFactory;

    protected $table = 'orders_products';

    protected $fillable = [
        'product_name',
        'orders_id',
        'products_id',
        'unit_price',
        'size',
        'color',
        'categories',
        'quantity',
    ];

    public function orders(): BelongsTo
    {
        return $this->belongsTo(orders::class);
    }
}
