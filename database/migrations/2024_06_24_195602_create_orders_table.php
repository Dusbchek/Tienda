<?php

use App\Models\orders;
use App\Models\products;
use App\Models\shipments;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('number');
            $table->foreignIdFor(shipments::class)->constrained()->onDelete('restrict');
            $table->enum('status', ['Nueva', 'En Proceso', 'Enviada', 'Entregada', 'Cancelada']);

            $table->string('receiver_name');
            /* $table->string('receiver_lastname'); */
            $table->string('receiver_phone');
            $table->string('receiver_email')->nullable();

            $table->string('receiver_street');
            $table->string('receiver_city');
            $table->string('receiver_state');
            $table->string('receiver_zip');

            $table->string('receiver_reference')->nullable();

            $table->decimal('shipment_price')->nullable();
            $table->decimal('subtotal')->nullable();

            $table->timestamps();
        });

        Schema::create('orders_products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->foreignIdFor(orders::class)->constrained()->onDelete('cascade');
            /* $table->foreignIdFor(products::class)->constrained()->onDelete('cascade'); */
            $table->string('size');
            $table->string('color');
            $table->json('categories');
            $table->integer('quantity');
            $table->decimal('unit_price');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('orders_products');
    }
};
