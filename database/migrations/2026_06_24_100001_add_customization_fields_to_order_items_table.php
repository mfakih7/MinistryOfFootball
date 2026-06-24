<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->boolean('customization_requested')->default(false)->after('total_price');
            $table->text('customization_details')->nullable()->after('customization_requested');
            $table->decimal('customization_fee', 10, 2)->default(0)->after('customization_details');
        });
    }

    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['customization_requested', 'customization_details', 'customization_fee']);
        });
    }
};
