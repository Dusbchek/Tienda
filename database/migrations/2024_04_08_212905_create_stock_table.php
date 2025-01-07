<?php

use App\Models\colors;
use App\Models\products;
use App\Models\sizes;
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
        Schema::create('stock', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(products::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(sizes::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(colors::class);
            $table->integer('stock');
            $table->integer('min_stock');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock');
    }
};
