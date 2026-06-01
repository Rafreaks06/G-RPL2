# Applicant Profile API Documentation

## Overview

Profile Applicant digunakan untuk melengkapi data pribadi sebelum membuat pengajuan RPL.

### Business Rule

Applicant wajib melengkapi profile sebelum membuat Application (A1, A2, atau Hybrid).

Jika profile belum lengkap, endpoint create application akan mengembalikan:

```json
{
    "message": "Please complete your profile before creating an application."
}
```

Status Code:

```http
422 Unprocessable Entity
```

---

# Get Profile

Mengambil data profile applicant yang sedang login.

## Endpoint

```http
GET /api/applicant/profile
```

## Headers

```http
Authorization: Bearer {token}
Accept: application/json
```

## Success Response

```json
{
    "success": true,
    "data": {
        "id": 1,
        "user_id": 1,
        "nik": "3276010101010001",
        "phone": "081234567890",
        "address": "Tangerang",
        "birth_place": "Jakarta",
        "birth_date": "2000-01-01",
        "gender": "male",
        "marital_status": "single",
        "nationality": "Indonesia",
        "postal_code": "15117",
        "last_education": "SMA",
        "institution_name": "SMAN 1 Tangerang",
        "study_program": null,
        "graduation_year": 2018,
        "created_at": "2026-06-01T00:00:00.000000Z",
        "updated_at": "2026-06-01T00:00:00.000000Z"
    }
}
```

---

# Update Profile

Melengkapi atau memperbarui data profile applicant.

## Endpoint

```http
PUT /api/applicant/profile
```

## Headers

```http
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

## Request Body

```json
{
    "birth_place": "Jakarta",
    "birth_date": "2000-01-01",
    "gender": "male",
    "marital_status": "single",
    "nationality": "Indonesia",
    "postal_code": "15117",
    "last_education": "SMA",
    "institution_name": "SMAN 1 Tangerang",
    "study_program": null,
    "graduation_year": 2018
}
```

## Validation Rules

| Field            | Required | Type    |
| ---------------- | -------- | ------- |
| birth_place      | nullable | string  |
| birth_date       | required | date    |
| gender           | required | string  |
| marital_status   | nullable | string  |
| nationality      | nullable | string  |
| postal_code      | nullable | string  |
| last_education   | required | string  |
| institution_name | nullable | string  |
| study_program    | nullable | string  |
| graduation_year  | required | integer |

## Success Response

```json
{
    "success": true,
    "message": "Profile updated successfully.",
    "data": {
        "id": 1,
        "user_id": 1,
        "nik": "3276010101010001",
        "phone": "081234567890",
        "address": "Tangerang",
        "birth_place": "Jakarta",
        "birth_date": "2000-01-01",
        "gender": "male",
        "marital_status": "single",
        "nationality": "Indonesia",
        "postal_code": "15117",
        "last_education": "SMA",
        "institution_name": "SMAN 1 Tangerang",
        "study_program": null,
        "graduation_year": 2018
    }
}
```

---

# Required Fields for Application Creation

Field berikut harus terisi sebelum applicant dapat membuat Application:

```text
birth_date
last_education
graduation_year
```

Jika salah satu field tersebut kosong:

```http
POST /api/applicant/applications
POST /api/applicant/applications/hybrid
```

akan mengembalikan:

```json
{
    "message": "Please complete your profile before creating an application."
}
```

Status Code:

```http
422 Unprocessable Entity
```
