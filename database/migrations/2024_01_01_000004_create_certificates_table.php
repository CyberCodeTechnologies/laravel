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
        Schema::create('certificates', function (Blueprint $table) {
            $table->id();
            $table->foreignId('artwork_id')->constrained()->onDelete('cascade');
            $table->foreignId('artist_id')->constrained('users')->onDelete('cascade');
            $table->string('certificate_code')->unique();
            $table->string('qr_code')->nullable(); // Path to QR code image
            $table->text('artist_signature')->nullable(); // Base64 encoded signature
            $table->text('panchi_signature')->nullable(); // Platform signature
            $table->date('issue_date');
            $table->text('certificate_text'); // Full certificate content
            $table->string('certificate_pdf')->nullable(); // Path to generated PDF
            $table->boolean('is_verified')->default(true);
            $table->timestamps();
            
            $table->index('certificate_code');
            $table->index(['artwork_id', 'is_verified']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('certificates');
    }
};
