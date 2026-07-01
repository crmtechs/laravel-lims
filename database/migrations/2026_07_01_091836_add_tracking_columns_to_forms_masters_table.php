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
        Schema::table('forms_masters', function (Blueprint $table)
        {
            $table->uuid('created_user_id')->nullable()->after('assigned_user_id');
            $table->uuid('modified_user_id')->nullable()->after('created_user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('forms_masters', function (Blueprint $table)
        {
            $table->dropColumn(['created_user_id', 'modified_user_id']);
        });
    }
};
