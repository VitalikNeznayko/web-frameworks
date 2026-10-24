<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('beds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->string('label', 10);
            $table->string('bed_type');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['room_id', 'label']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('beds');
    }
};
