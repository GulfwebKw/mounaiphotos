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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_id');
            $table->string('uuid')->unique()->index();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->text('message')->nullable();
            $table->dateTime('from')->index();
            $table->dateTime('to')->index();
            $table->string('price');
            $table->string('invoice_id')->nullable();
            $table->string('reference_number')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->dateTime('paid_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
