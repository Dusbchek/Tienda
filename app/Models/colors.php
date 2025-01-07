<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class colors extends Model
{
    use HasFactory;

    protected $fillable = [
        'color',
        'hexadecimal'
    ];

    public function products() {
        return $this->BelongsToMany(products::class, 'product_colors');
    }

    public function images() {
        return $this->hasMany(images::class);
    }

    public function stock(){
        return $this->hasMany(stock::class);
    }
}
