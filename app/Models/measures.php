<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class measures extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [
        'products_id',
        'sizes_id',
        'part',
        'measure',
    ];

    public function products()
    {
        return $this->belongsTo(products::class);
    }

    public function sizes()
    {
        return $this->belongsTo(sizes::class);
    }

}

