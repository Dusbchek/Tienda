<?php

use App\Models\products;
use App\Models\products_size;
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
        Schema::create('measures', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(products::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(sizes::class)->constrained('sizes')->onDelete('cascade');
            $table->string('part');
            $table->integer('measure');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('measures');
    }
};
