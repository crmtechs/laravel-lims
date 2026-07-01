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
        Schema::table('annexures_masters', function (Blueprint $table) {
            $table->foreignUuid('annexures_masters_revision_uuid')->nullable()->constrained('annexures_masters_revisions', 'uuid')->nullOnDelete()->after('assigned_user_id');
            
            $table->dropColumn(['file_path', 'file_name', 'file_ext', 'file_mime_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('annexures_masters', function (Blueprint $table) {
            $table->dropForeign(['annexures_masters_revision_uuid']);
            $table->dropColumn('annexures_masters_revision_uuid');
            
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_ext', 10)->nullable();
            $table->string('file_mime_type', 100)->nullable();
        });
    }
};
