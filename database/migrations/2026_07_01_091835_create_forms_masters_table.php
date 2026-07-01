<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('forms_masters', function (Blueprint $table)
        {
            $table->uuid('uuid')->primary();
            $table->date('publish_date')->nullable();
            $table->date('expiration_date')->nullable();
            $table->string('status')->nullable();
            $table->string('document_name')->nullable();
            $table->text('description')->nullable();
            $table->string('document_title')->nullable();
            $table->foreignUuid('assigned_user_id')->nullable()->references('uuid')->on('users')->nullOnDelete();
            $table->foreignUuid('created_user_id')->nullable()->references('uuid')->on('users')->nullOnDelete();
            $table->foreignUuid('modified_user_id')->nullable()->references('uuid')->on('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('forms_masters_revisions', function (Blueprint $table)
        {
            $table->uuid('uuid')->primary();
            $table->foreignUuid('forms_master_uuid')->constrained('forms_masters', 'uuid')->onDelete('cascade');
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

        Schema::table('forms_masters', function (Blueprint $table)
        {
            $table->foreignUuid('forms_masters_revision_uuid')->nullable()->constrained('forms_masters_revisions', 'uuid')->nullOnDelete()->after('assigned_user_id');
        });
    }

    public function down()
    {
        Schema::table('forms_masters', function (Blueprint $table)
        {
            $table->dropForeign(['forms_masters_revision_uuid']);
            $table->dropColumn('forms_masters_revision_uuid');
        });

        Schema::dropIfExists('forms_masters_revisions');
        Schema::dropIfExists('forms_masters');
    }
};
