<?php

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
        Schema::create('sizes', function (Blueprint $table) {
            $table->id();
            $table->string('size')->unique();
            $table->timestamps();
        });

        Schema::create('products_sizes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(products::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(sizes::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sizes');
        Schema::dropIfExists('products_sizes');
    }
};
