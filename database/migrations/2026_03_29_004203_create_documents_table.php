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
        Schema::create('documents', function (Blueprint $table) {

            $table->id();

            $table->foreignId('document_type_id')
                ->constrained()
                ->restrictOnDelete();

            $table->string('title');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // SOLO LA COLUMNA
            $table->unsignedBigInteger('current_version_id')
                ->nullable();

            $table->enum('status', ['draft', 'active', 'archived'])->default('draft');

            $table->foreignId('updated_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(['title', 'document_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
