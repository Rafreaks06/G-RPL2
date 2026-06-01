<?php

namespace App\Services;

use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\Applicant;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ApplicationDocumentService
{
    /**
     * Get all documents.
     */
    public function index(
        int $applicationId,
        Applicant $applicant
    )
    {
        $application =
            $this->getOwnedApplication(
                $applicationId,
                $applicant
            );

        return $application
            ->documents()
            ->latest()
            ->get();
    }

    /**
     * Get document detail.
     */
    public function show(
        int $applicationId,
        int $documentId,
        Applicant $applicant
    ): ApplicationDocument {

        $application =
            $this->getOwnedApplication(
                $applicationId,
                $applicant
            );

        return $application
            ->documents()
            ->findOrFail(
                $documentId
            );
    }

    /**
     * Upload document.
     */
    public function store(
        int $applicationId,
        Applicant $applicant,
        array $data
    ): ApplicationDocument {

        return DB::transaction(
            function () use (
                $applicationId,
                $applicant,
                $data
            ) {

                $application =
                    $this->getOwnedApplication(
                        $applicationId,
                        $applicant
                    );

                /** @var UploadedFile $file */
                $file = $data['file'];

                $path = $file->store(
                    'application-documents',
                    'public'
                );

                return ApplicationDocument::create([

                    'application_id'
                        => $application->id,

                    'document_type'
                        => $data['document_type'],

                    'document_name'
                        => $data['document_name'],

                    'file_name'
                        => $file->getClientOriginalName(),

                    'file_path'
                        => $path,

                    'mime_type'
                        => $file->getMimeType(),

                    'file_size'
                        => $file->getSize(),
                ]);
            }
        );
    }

    /**
     * Update document.
     */
    public function update(
        int $applicationId,
        int $documentId,
        Applicant $applicant,
        array $data
    ): ApplicationDocument {

        return DB::transaction(
            function () use (
                $applicationId,
                $documentId,
                $applicant,
                $data
            ) {

                $application =
                    $this->getOwnedApplication(
                        $applicationId,
                        $applicant
                    );

                $document =
                    $application
                        ->documents()
                        ->findOrFail(
                            $documentId
                        );

                $payload = [

                    'document_type'
                        => $data['document_type'],

                    'document_name'
                        => $data['document_name'],
                ];

                /*
                | Replace File
                */

                if (
                    isset($data['file'])
                ) {

                    Storage::disk('public')
                        ->delete(
                            $document->file_path
                        );

                    /** @var UploadedFile $file */
                    $file = $data['file'];

                    $path = $file->store(
                        'application-documents',
                        'public'
                    );

                    $payload = array_merge(
                        $payload,
                        [

                            'file_name'
                                => $file->getClientOriginalName(),

                            'file_path'
                                => $path,

                            'mime_type'
                                => $file->getMimeType(),

                            'file_size'
                                => $file->getSize(),
                        ]
                    );
                }

                $document->update(
                    $payload
                );

                return $document->fresh();
            }
        );
    }

     /**
     * Download document.
     */
    public function download(
        int $applicationId,
        int $documentId,
        Applicant $applicant
    ) {

        $application =
            $this->getOwnedApplication(
                $applicationId,
                $applicant
            );

        $document =
            $application
                ->documents()
                ->findOrFail(
                    $documentId
                );

        if (
            !Storage::disk('public')
                ->exists(
                    $document->file_path
                )
        ) {

            abort(
                404,
                'Document file not found.'
            );
        }

        return Storage::disk('public')
            ->download(
                $document->file_path,
                $document->file_name
            );
    }

    /**
     * Validate application ownership.
     */
    private function getOwnedApplication(
        int $applicationId,
        Applicant $applicant
    ): Application {

        return Application::query()

            ->where(
                'applicant_id',
                $applicant->id
            )

            ->findOrFail(
                $applicationId
            );
    }
}