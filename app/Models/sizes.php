<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class sizes extends Model
{
    use HasFactory;

    protected $fillable = [
        'size',

    ];

    public function products()
    {
        return $this->belongsToMany(products::class, 'product_sizes');
    }

    public function measures()
    {
        return $this->hasMany(measures::class);
    }

    public function stock(){
        return $this->hasMany(stock::class);
    }
}
