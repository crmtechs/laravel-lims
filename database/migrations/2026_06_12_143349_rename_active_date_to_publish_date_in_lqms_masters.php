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
        Schema::table('lqms_masters', function (Blueprint $table) {
            $table->renameColumn('active_date', 'publish_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lqms_masters', function (Blueprint $table) {
            $table->renameColumn('publish_date', 'active_date');
        });
    }
};
