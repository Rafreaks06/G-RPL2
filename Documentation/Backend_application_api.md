# Applicant Application Module API Documentation

## Base URL

```http
/api/applicant
```

---

# Business Flow

# Create Application Header

Applicant harus membuat Application Header terlebih dahulu sebelum mengisi data A1, A2, maupun mengunggah dokumen pendukung.

Semua application baru akan dibuat dengan status:

```text
draft
```

---

## A1 Application Header

### Endpoint

```http
POST /api/applicant/applications
```

### Request Body

```json
{
    "study_program_id": 2,
    "rpl_type": "a1"
}
```

### Validation

| Field            | Required | Description               |
| ---------------- | -------- | ------------------------- |
| study_program_id | Yes      | Existing study program ID |
| rpl_type         | Yes      | Must be `a1`              |

### Success Response

```json
{
    "success": true,
    "message": "Application created successfully.",
    "data": {
        "id": 1,
        "application_number": "RPL-2026-F46KEX",
        "status": "draft",
        "rpl_type": "a1"
    }
}
```

---

## A2 Application Header

### Endpoint

```http
POST /api/applicant/applications
```

### Request Body

```json
{
    "study_program_id": 3,
    "rpl_type": "a2"
}
```

### Validation

| Field            | Required | Description               |
| ---------------- | -------- | ------------------------- |
| study_program_id | Yes      | Existing study program ID |
| rpl_type         | Yes      | Must be `a2`              |

### Success Response

```json
{
    "success": true,
    "message": "Application created successfully.",
    "data": {
        "id": 2,
        "application_number": "RPL-2026-ULPPJE",
        "status": "draft",
        "rpl_type": "a2"
    }
}
```

---

## Hybrid Application Header

### Endpoint

```http
POST /api/applicant/applications/hybrid
```

### Request Body

```json
{
    "study_program_id": 2,
    "rpl_type": "hybrid"
}
```

### Validation

| Field            | Required | Description               |
| ---------------- | -------- | ------------------------- |
| study_program_id | Yes      | Existing study program ID |
| rpl_type         | Yes      | Must be `hybrid`          |

### Success Response

```json
{
    "success": true,
    "message": "Hybrid application created successfully.",
    "data": {
        "id": 3,
        "application_number": "RPL-2026-ZNL5TX",
        "status": "draft",
        "rpl_type": "hybrid"
    }
}
```

---

## Study Program Validation

Application type must be supported by the selected study program.

### Example Rules

```text
supports_a1 = true
supports_a2 = false
is_hybrid_allowed = false
```

Allowed:

```text
A1
```

Rejected:

```text
A2
Hybrid
```

### Error Response

```json
{
    "message": "Selected study program does not support A2 applications."
}
```

## Hybrid RPL Flow

```text
1. Create Application Header
2. Add A1 Courses
3. Add A2 Learning Experiences
4. Upload Supporting Documents
5. Submit Application
```

---

# Step 1 - Create Application Header

## Endpoint

```http
POST /api/applicant/applications/hybrid
```

## Request Body

```json
{
    "study_program_id": 2,
    "rpl_type": "hybrid"
}
```

## Validation

| Field            | Required | Description               |
| ---------------- | -------- | ------------------------- |
| study_program_id | Yes      | Existing study program ID |
| rpl_type         | Yes      | Must be `hybrid`          |

## Success Response

```json
{
    "success": true,
    "message": "Hybrid application created successfully.",
    "data": {
        "id": 3,
        "application_number": "RPL-2026-ZNL5TX",
        "status": "draft",
        "rpl_type": "hybrid"
    }
}
```

---

# Step 2 - Add A1 Course

## Endpoint

```http
POST /api/applicant/applications/{id_application}/a1-courses
```

## Request Body

```json
{
    "course_code": "IF101",
    "course_name": "Algoritma dan Pemrograman",
    "credits": 3,
    "grade": "A",
    "institution_name": "Universitas ABC"
}
```

## Validation

| Field            | Required |
| ---------------- | -------- |
| course_code      | Yes      |
| course_name      | Yes      |
| credits          | Yes      |
| grade            | Yes      |
| institution_name | Yes      |

---

# Step 3 - Add A2 Learning Experience

## Endpoint

```http
POST /api/applicant/applications/{id_application}/a2-learning-experiences
```

## Request Body

```json
{
    "title": "Backend Developer",
    "experience_type": "work",
    "organization_name": "PT Teknologi Indonesia",
    "start_date": "2022-01-01",
    "end_date": "2025-01-01",
    "is_ongoing": false,
    "description": "Mengembangkan REST API menggunakan Golang."
}
```

## Validation

| Field             | Required |
| ----------------- | -------- |
| title             | Yes      |
| experience_type   | Yes      |
| organization_name | Yes      |
| is_ongoing        | Yes      |
| description       | Yes      |

---

# Step 4 - Upload Supporting Document

## Endpoint

```http
POST /api/applicant/applications/{id_application}/documents
```

## Content Type

```http
multipart/form-data
```

## Form Data

| Key           | Type | Required |
| ------------- | ---- | -------- |
| document_type | Text | Yes      |
| document_name | Text | Yes      |
| file          | File | Yes      |

## Example

```text
document_type = Transkrip Nilai
document_name = transcript
file = transcript.pdf
```

---

# Step 5 - Submit Application

## Endpoint

```http
POST /api/applicant/applications/{id_application}/submit
```

## Request Body

```json
{}
```

---

# Submit Validation Rules

## A1

Requirements:

```text
- Minimum 1 A1 Course
- Minimum 1 Supporting Document
```

---

## A2

Requirements:

```text
- Minimum 1 Learning Experience
- Minimum 1 Supporting Document
```

---

## Hybrid

Requirements:

```text
- Minimum 1 A1 Course
- Minimum 1 Learning Experience
- Minimum 2 Supporting Documents
```

---

# Common Error Responses

## Validation Error

```json
{
    "message": "The given data was invalid.",
    "errors": {}
}
```

---

## A1 Course Missing

```json
{
    "message": "At least one A1 course is required."
}
```

---

## Learning Experience Missing

```json
{
    "message": "At least one learning experience is required."
}
```

---

## Hybrid Document Validation

```json
{
    "message": "At least two supporting documents are required for Hybrid RPL."
}
```

---

# Supporting Endpoints

## Applications

```http
GET    /api/applicant/applications
GET    /api/applicant/applications/{id_application}
PUT    /api/applicant/applications/{id_application}
```

---

## Hybrid Applications

```http
GET    /api/applicant/applications/hybrid
GET    /api/applicant/applications/hybrid/{id_application}
PUT    /api/applicant/applications/hybrid/{id_application}
```

---

## A1 Courses

```http
GET    /api/applicant/applications/{id_application}/a1-courses
GET    /api/applicant/applications/{id_application}/a1-courses/{id_course}
PUT    /api/applicant/applications/{id_application}/a1-courses/{id_course}
```

---

## A2 Learning Experiences

```http
GET    /api/applicant/applications/{id_application}/a2-learning-experiences
GET    /api/applicant/applications/{id_application}/a2-learning-experiences/{id_experience}
PUT    /api/applicant/applications/{id_application}/a2-learning-experiences/{id_experience}
```

---

## Documents

```http
GET    /api/applicant/applications/{id_application}/documents
GET    /api/applicant/applications/{id_application}/documents/{id_document}
GET    /api/applicant/applications/{id_application}/documents/{id_document}/download
PUT    /api/applicant/applications/{id_application}/documents/{id_document}
```
