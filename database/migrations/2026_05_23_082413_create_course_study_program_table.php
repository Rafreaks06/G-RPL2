<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run migrations.
     */
    public function up(): void
    {
        Schema::create(
            'course_study_program',
            function (Blueprint $table) {

                $table->id();

                $table->foreignId('course_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->foreignId('study_program_id')
                    ->constrained()
                    ->cascadeOnDelete();

                $table->timestamps();
            }
        );
    }

    /**
     * Reverse migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(
            'course_study_program'
        );
    }
};