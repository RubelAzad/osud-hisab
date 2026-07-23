<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_rates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->decimal('rate', 5, 2);
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        Schema::table('medicines', function (Blueprint $table) {
            $table->foreignId('tax_rate_id')->nullable()->after('warranty_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tax_rate_id');
        });

        Schema::dropIfExists('tax_rates');
    }
};
