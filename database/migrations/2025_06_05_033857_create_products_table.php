<?php

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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('app_name', 200);
            $table->text('description');
            $table->string('cover_img')->nullable();
            $table->string('notes', 150)->nullable();
            $table->boolean('is_topup')->default(false);
            $table->boolean('ready_stock')->default(true);
            $table->foreignId('category_id')->constrained('product_categories')->cascadeOnDelete();
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
