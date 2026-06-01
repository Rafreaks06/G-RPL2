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
        Schema::create('applications', function (Blueprint $table) {

            $table->id();

            /*
            | Applicant
            */

            $table->foreignId('applicant_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            | Target Study Program
            */

            $table->foreignId('study_program_id')
                ->constrained()
                ->cascadeOnDelete();

            /*
            | Application Information
            */

            $table->string('application_number')
                ->unique();

            $table->enum('rpl_type', [
                'a1',
                'a2',
                'hybrid',
            ]);

            $table->enum('status', [
                'draft',
                'submitted',
                'under_review',
                'returned',
                'under_assessment',
                'assessed',
                'approved',
                'rejected',
            ])->default('draft');

            /*
            | Review Information
            */

            $table->text('review_notes')
                ->nullable();

            $table->unsignedInteger('revision_count')
                ->default(0);

            /*
            | Submission
            */

            $table->timestamp('submitted_at')
                ->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applications');
    }
};