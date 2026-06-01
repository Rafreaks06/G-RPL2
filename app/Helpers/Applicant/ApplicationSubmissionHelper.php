<?php

namespace App\Helpers\Applicant;

use App\Enums\RplType;
use App\Models\Application;

class ApplicationSubmissionHelper
{
    /**
     * Validate application before submit.
     */
    public function validate(
        Application $application
    ): void {

        $application->load([
            'a1Courses',
            'a2LearningExperiences',
            'documents',
        ]);

        switch (
            $application->rpl_type
        ) {

            case RplType::A1:

                $this->validateA1(
                    $application
                );

                break;

            case RplType::A2:

                $this->validateA2(
                    $application
                );

                break;

            case RplType::HYBRID:

                $this->validateHybrid(
                    $application
                );

                break;
        }
    }

    /**
     * Validate A1 submission.
     */
    private function validateA1(
        Application $application
    ): void {

        if (
            $application
                ->a1Courses
                ->isEmpty()
        ) {

            abort(
                422,
                'At least one A1 course is required.'
            );
        }

        if (
            $application
                ->documents
                ->isEmpty()
        ) {

            abort(
                422,
                'At least one document is required.'
            );
        }
    }

    /**
     * Validate A2 submission.
     */
    private function validateA2(
        Application $application
    ): void {

        if (
            $application
                ->a2LearningExperiences
                ->isEmpty()
        ) {

            abort(
                422,
                'At least one learning experience is required.'
            );
        }

        if (
            $application
                ->documents
                ->isEmpty()
        ) {

            abort(
                422,
                'At least one document is required.'
            );
        }
    }

    /**
     * Validate Hybrid submission.
     */
    private function validateHybrid(
        Application $application
    ): void {

        if (
            $application
                ->a1Courses
                ->isEmpty()
        ) {

            abort(
                422,
                'At least one A1 course is required.'
            );
        }

        if (
            $application
                ->a2LearningExperiences
                ->isEmpty()
        ) {

            abort(
                422,
                'At least one learning experience is required.'
            );
        }

        /*
        | Hybrid Requires A1 Evidence + A2 Evidence
        */

        if (
            $application
                ->documents
                ->count() < 2
        ) {

            abort(
                422,
                'At least two supporting documents are required for Hybrid RPL.'
            );
        }
    }
}