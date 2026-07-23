<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('warranties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->unsignedInteger('duration_days');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::table('medicines', function (Blueprint $table) {
            $table->foreignId('warranty_id')->nullable()->after('unit_id')->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('medicines', function (Blueprint $table) {
            $table->dropConstrainedForeignId('warranty_id');
        });

        Schema::dropIfExists('warranties');
    }
};
