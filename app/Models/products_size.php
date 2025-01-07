<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class products_size extends Model
{
    use HasFactory;

    protected $fillable = [
        'size',
        'size_id'
    ];

    public function products()
    {
        return $this->belongsTo(products::class);
    }

    public function sizes()
    {
        return $this->belongsTo(sizes::class);
    }

    public function measures(){
        return $this->hasMany(measures::class);
    }

}
