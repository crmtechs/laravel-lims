<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('annexures_masters', function (Blueprint $table)
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

        Schema::create('annexures_masters_revisions', function (Blueprint $table)
        {
            $table->uuid('uuid')->primary();
            $table->foreignUuid('annexures_master_uuid')->constrained('annexures_masters', 'uuid')->onDelete('cascade');
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

        Schema::table('annexures_masters', function (Blueprint $table)
        {
            $table->foreignUuid('annexures_masters_revision_uuid')->nullable()->constrained('annexures_masters_revisions', 'uuid')->nullOnDelete()->after('assigned_user_id');
        });
    }

    public function down()
    {
        Schema::table('annexures_masters', function (Blueprint $table)
        {
            $table->dropForeign(['annexures_masters_revision_uuid']);
            $table->dropColumn('annexures_masters_revision_uuid');
        });

        Schema::dropIfExists('annexures_masters_revisions');
        Schema::dropIfExists('annexures_masters');
    }
};
