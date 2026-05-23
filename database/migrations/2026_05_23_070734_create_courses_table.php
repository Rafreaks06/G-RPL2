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
        Schema::create('courses', function (
            Blueprint $table
        ) {

            $table->id();

            /*
            | Course Information
            */

            $table->string('code')
                ->unique();

            $table->string('name');

            $table->unsignedTinyInteger('semester');

            $table->unsignedTinyInteger('sks');

            /*
            | RPL Configuration
            */

            $table->enum('rpl_type', [
                'a1',
                'a2',
                'hybrid',
                'not_supported',
            ])->default('not_supported');

            /*
            | Status
            */

            $table->enum('status', [
                'active',
                'inactive',
            ])->default('active');

            $table->boolean('is_active')
                ->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};