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
        Schema::table('reservations', function (Blueprint $table) {
            $table->integer('number_of_persons')->default(1)->after('price');
        });
        Schema::table('packages', function (Blueprint $table) {
            $table->integer('number_of_persons')->default(1)->after('picture');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reservations', function (Blueprint $table) {
            $table->dropColumn('number_of_persons');
        });
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn('number_of_persons');
        });
    }
};
