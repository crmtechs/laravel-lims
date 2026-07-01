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
        Schema::create('forms_masters', function (Blueprint $table)
        {
            $table->uuid('uuid')->primary();
            $table->date('active_date')->nullable();
            $table->string('status')->nullable();
            $table->string('document_name')->nullable();
            $table->text('description')->nullable();
            $table->string('file_ext')->nullable();
            $table->string('file_mime_type')->nullable();
            $table->string('document_title')->nullable();
            $table->string('file_path')->nullable();
            $table->foreignUuid('assigned_user_id')->nullable()->references('uuid')->on('users')->nullOnDelete();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('forms_masters');
    }
};
