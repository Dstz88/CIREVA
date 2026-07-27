<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperation_agreements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_profile_id')->constrained()->cascadeOnDelete();
            $table->string('agreement_number', 100)->unique();
            $table->string('version', 20);
            $table->string('file_path', 255)->nullable();
            $table->dateTime('signed_at')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->text('rejected_reason')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->enum('status', ['draft', 'generated', 'pending_signature', 'signed', 'under_review', 'revision_required', 'approved', 'rejected', 'expired'])->default('draft')->index();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperation_agreements');
    }
};