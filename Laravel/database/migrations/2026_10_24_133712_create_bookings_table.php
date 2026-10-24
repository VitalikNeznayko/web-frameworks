<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('guest_id')->constrained()->restrictOnDelete();
            $table->foreignId('bed_id')->constrained()->restrictOnDelete();
            $table->date('check_in');
            $table->date('check_out');
            $table->string('status')->default('pending');
            $table->decimal('total_price', 10, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
