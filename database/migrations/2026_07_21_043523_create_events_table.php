<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_profile_id')->constrained()->cascadeOnDelete();
            $table->foreignId('category_id')->constrained('event_categories')->restrictOnDelete();
            $table->foreignId('location_id')->constrained('event_locations')->restrictOnDelete();
            $table->string('title', 200);
            $table->string('slug', 220)->unique();
            $table->longText('description');
            $table->string('banner', 255);
            $table->enum('status', ['draft', 'submitted', 'under_review', 'revision_required', 'approved', 'published', 'ongoing', 'finished', 'archived'])->default('draft')->index();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('approved_at')->nullable();
            $table->foreignId('published_by')->nullable()->constrained('users')->nullOnDelete();
            $table->dateTime('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
