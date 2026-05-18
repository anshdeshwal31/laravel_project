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
        Schema::create('funding_request_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('funding_request_id')
                ->constrained('funding_requests')
                ->cascadeOnDelete();
            $table->string('status')->default('pending'); // pending, processing, completed, failed
            $table->integer('overall_score')->nullable(); // 0 - 100
            $table->text('summary')->nullable();
            $table->json('strengths')->nullable(); // JSON array
            $table->json('weaknesses')->nullable(); // JSON array
            $table->json('risks')->nullable(); // JSON array
            $table->text('verdict')->nullable();
            $table->text('error_message')->nullable();
            $table->longText('raw_analysis')->nullable(); // Original response from Groq
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('funding_request_reviews');
    }
};
