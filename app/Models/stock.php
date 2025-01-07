<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class stock extends Model
{
    use HasFactory;

    protected $table = 'stock';


    protected $fillable = [
        'products_id',
        'sizes_id',
        'colors_id',
        'stock',
        'min_stock'
    ];

    public function products()
    {
        return $this->belongsTo(Products::class);
    }

    public function sizes()
    {
        return $this->belongsTo(sizes::class);
    }
    
    public function colors(){
        return $this->belongsTo(colors::class);
    }
}
