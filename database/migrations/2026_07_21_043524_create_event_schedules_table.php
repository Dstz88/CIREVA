<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('event_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->string('timezone', 50);
            $table->enum('status', ['draft', 'scheduled', 'published', 'ongoing', 'finished', 'cancelled'])->default('draft')->index();
            $table->timestamps();

            $table->index(['event_id', 'start_datetime']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('event_schedules');
    }
};
