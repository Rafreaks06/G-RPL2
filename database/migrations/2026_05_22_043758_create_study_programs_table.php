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
        Schema::create('study_programs', function (Blueprint $table) {

            $table->id();

            /*
            | Basic Information
            */

            $table->string('code')
                ->unique();

            $table->string('name');

            /*
            | Academic Information
            */

            $table->unsignedInteger('total_sks')
                ->default(144);

            $table->unsignedInteger('max_convertible_sks')
                ->default(100);

            /*
            | RPL Scheme Support
            */

            $table->boolean('supports_a1')
                ->default(true);

            $table->boolean('supports_a2')
                ->default(true);

            $table->boolean('is_hybrid_allowed')
                ->default(true);

            /*
            | Status
            */

            $table->enum('status', [
                'active',
                'inactive'
            ])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('study_programs');
    }
};