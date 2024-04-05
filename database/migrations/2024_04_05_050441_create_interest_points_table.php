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
        Schema::create('interest_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('x')->unsigned();
            $table->integer('y')->unsigned();
            $table->time('opened')->nullable();
            $table->time('closed')->nullable();
            $table->boolean('always_open')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('interest_points');
    }
};
