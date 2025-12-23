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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distributor_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->text('address');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->integer('stock');
            $table->integer('capacity')->nullable();
            $table->boolean('is_open');
            $table->string('phone')->nullable();
            $table->string('photo')->nullable();
            $table->string('operating_hours')->nullable();
            $table->timestamps();

            $table->index('distributor_id');
            $table->index('is_open');
            $table->index('latitude');
            $table->index('longitude');
            $table->index(['latitude', 'longitude']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('locations');
    }
};
