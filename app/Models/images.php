<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class images extends Model
{
    use HasFactory;



    protected $fillable = [
        'products_id',
        'image',
        "colors_id",
    ];

    public function Products()
    {
        return $this->belongsTo(products::class);
    }

    public function Colors()
    {
        return $this->belongsTo(Colors::class);
    }
}
