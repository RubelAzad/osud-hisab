<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->string('shipping_status')->nullable()->after('note');
            $table->string('shipping_address')->nullable()->after('shipping_status');
            $table->timestamp('shipped_at')->nullable()->after('shipping_address');
            $table->string('channel')->nullable()->after('shipped_at');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['shipping_status', 'shipping_address', 'shipped_at', 'channel']);
        });
    }
};
