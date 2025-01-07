<?php

use App\Models\colors;
use App\Models\products;
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
        Schema::create('colors', function (Blueprint $table) {
            $table->id();
            $table->string('color')->unique();
            $table->string('hexadecimal');
            $table->timestamps();
        });
        Schema::create('product_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(products::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(colors::class)->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('colors');
        Schema::dropIfExists('product_colors');
    }
};
