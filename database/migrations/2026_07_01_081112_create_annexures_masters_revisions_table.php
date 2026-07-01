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
        Schema::create('annexures_masters_revisions', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->foreignUuid('annexures_master_uuid')->constrained('annexures_masters', 'uuid')->onDelete('cascade');
            $table->string('revision');
            $table->string('file_path')->nullable();
            $table->string('file_name')->nullable();
            $table->string('file_ext', 10)->nullable();
            $table->string('file_mime_type', 100)->nullable();
            $table->foreignUuid('created_user_id')->nullable()->constrained('users', 'uuid')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('annexures_masters_revisions');
    }
};
