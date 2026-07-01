<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lqms_masters', function (Blueprint $table)
        {
            $table->uuid('uuid')->primary();
            $table->date('publish_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('status')->nullable();
            $table->string('document_name')->nullable();
            $table->text('description')->nullable();
            $table->string('document_title')->nullable();
            $table->foreignUuid('assigned_user_id')->nullable()->references('uuid')->on('users')->nullOnDelete();
            $table->uuid('created_user_id')->nullable();
            $table->uuid('modified_user_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('lqms_masters_revisions', function (Blueprint $table)
        {
            $table->uuid('uuid')->primary();
            $table->foreignUuid('lqms_master_uuid')->constrained('lqms_masters', 'uuid')->onDelete('cascade');
            $table->text('change_log')->nullable();
            $table->string('revision');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_ext', 10)->nullable();
            $table->string('file_mime_type', 100)->nullable();
            $table->foreignUuid('created_user_id')->nullable()->constrained('users', 'uuid')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('lqms_masters', function (Blueprint $table)
        {
            $table->foreignUuid('lqms_masters_revision_uuid')->nullable()->constrained('lqms_masters_revisions', 'uuid')->nullOnDelete()->after('assigned_user_id');
        });
    }

    public function down(): void
    {
        Schema::table('lqms_masters', function (Blueprint $table)
        {
            $table->dropForeign(['lqms_masters_revision_uuid']);
            $table->dropColumn('lqms_masters_revision_uuid');
        });

        Schema::dropIfExists('lqms_masters_revisions');
        Schema::dropIfExists('lqms_masters');
    }
};
