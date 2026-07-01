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
        Schema::table('lqms_masters_revisions', function (Blueprint $table) {
            if (!Schema::hasColumn('lqms_masters_revisions', 'change_log')) {
                $table->text('change_log')->nullable()->after('lqms_master_uuid');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lqms_masters_revisions', function (Blueprint $table) {
            if (Schema::hasColumn('lqms_masters_revisions', 'change_log')) {
                $table->dropColumn('change_log');
            }
        });
    }
};
