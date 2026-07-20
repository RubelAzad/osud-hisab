<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained()->restrictOnDelete();
            $table->foreignId('manufacturer_id')->constrained()->restrictOnDelete();
            $table->foreignId('generic_id')->constrained()->restrictOnDelete();
            $table->foreignId('medicine_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('unit_id')->constrained()->restrictOnDelete();
            $table->string('barcode')->nullable();
            $table->string('medicine_name');
            $table->string('strength')->nullable();
            $table->decimal('purchase_price', 10, 2)->default(0);
            $table->decimal('sale_price', 10, 2)->default(0);
            $table->unsignedInteger('minimum_stock')->default(0);
            $table->decimal('vat', 5, 2)->default(0);
            $table->boolean('status')->default(true);
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();

            $table->unique(['pharmacy_id', 'barcode']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
