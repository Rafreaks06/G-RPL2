<?php

namespace App\Http\Requests\Applicant;

use Illuminate\Foundation\Http\FormRequest;

abstract class ApplicantRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'required' => ':attribute wajib diisi.',
            'string' => ':attribute harus berupa teks.',
            'integer' => ':attribute harus berupa angka.',
            'boolean' => ':attribute tidak valid.',
            'date' => ':attribute harus berupa tanggal yang valid.',
            'file' => ':attribute harus berupa berkas.',
            'mimes' => ':attribute harus berupa berkas dengan format: :values.',
            'max.string' => ':attribute maksimal :max karakter.',
            'max.file' => ':attribute maksimal :max kilobyte.',
            'max.numeric' => ':attribute maksimal :max.',
            'min.numeric' => ':attribute minimal :min.',
            'exists' => ':attribute yang dipilih tidak valid.',
            'in' => ':attribute yang dipilih tidak valid.',
            'end_date.after_or_equal' => 'Tanggal selesai harus sama dengan atau setelah tanggal mulai.',
        ];
    }

    public function attributes(): array
    {
        return [
            'study_program_id' => 'program studi',
            'rpl_type' => 'tipe RPL',
            'course_code' => 'kode mata kuliah',
            'course_name' => 'nama mata kuliah',
            'credits' => 'SKS',
            'grade' => 'nilai',
            'institution_name' => 'nama institusi',
            'title' => 'judul',
            'experience_type' => 'jenis pengalaman',
            'organization_name' => 'nama organisasi',
            'start_date' => 'tanggal mulai',
            'end_date' => 'tanggal selesai',
            'is_ongoing' => 'status masih berjalan',
            'description' => 'deskripsi',
            'document_type' => 'jenis dokumen',
            'document_name' => 'nama dokumen',
            'file' => 'berkas',
            'birth_place' => 'tempat lahir',
            'birth_date' => 'tanggal lahir',
            'gender' => 'jenis kelamin',
            'marital_status' => 'status perkawinan',
            'nationality' => 'kewarganegaraan',
            'postal_code' => 'kode pos',
            'phone' => 'nomor telepon',
            'address' => 'alamat',
            'last_education' => 'pendidikan terakhir',
            'study_program' => 'program studi',
            'graduation_year' => 'tahun lulus',
        ];
    }
}
