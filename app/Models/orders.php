<?php

namespace App\Models;

use App\Enums\OrderStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class orders extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
        'status',
        'shipments_id',
        'receiver_name',
        'receiver_email',
        'receiver_phone',
        'receiver_street',
        'receiver_city',
        'receiver_state',
        'receiver_zip',
        'receiver_reference',
        'shipment_price',
        'subtotal',
    ];

    protected $casts = [
        'status' => OrderStatus::class,
    ];

    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProducts::class);
    }

    public function shipments(): BelongsTo
    {
        return $this->belongsTo(shipments::class);
    }
}
