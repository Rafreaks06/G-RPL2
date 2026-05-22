<?php

namespace App\Services;

use App\Models\StudyProgram;

class StudyProgramService
{
    /*
    | Get All Study Programs
    */

    public function getAll()
    {
        return StudyProgram::latest()
            ->get();
    }

    /*
    | Get Study Program Detail
    */

    public function getById(
        StudyProgram $studyProgram
    )
    {
        return $studyProgram;
    }

    /*
    | Create Study Program
    */

    public function store($request)
    {
        $studyProgram = StudyProgram::create([

            'code' => $request->code,

            'name' => $request->name,

            'total_sks' => $request->total_sks,

            'max_convertible_sks' => $request->max_convertible_sks,

            'supports_a1' => $request->supports_a1,

            'supports_a2' => $request->supports_a2,

            'is_hybrid_allowed' => $request->is_hybrid_allowed,

            'status' => $request->status,
        ]);

        return [
            'success' => true,

            'message' => 'Study program created successfully',

            'data' => $studyProgram,
        ];
    }

    /*
    | Update Study Program
    */

    public function update(
        $request,
        StudyProgram $studyProgram
    )
    {
        $studyProgram->update([

            'code' => $request->code,

            'name' => $request->name,

            'total_sks' => $request->total_sks,

            'max_convertible_sks' => $request->max_convertible_sks,

            'supports_a1' => $request->supports_a1,

            'supports_a2' => $request->supports_a2,

            'is_hybrid_allowed' => $request->is_hybrid_allowed,

            'status' => $request->status,
        ]);

        return [
            'success' => true,

            'message' => 'Study program updated successfully',

            'data' => $studyProgram->fresh(),
        ];
    }
}