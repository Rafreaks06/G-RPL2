# Staff RPL API Documentation

## Base URL

```http
/api/staff
```

## Authentication

All endpoints require:

```http
Authorization: Bearer {token}
```

Role required:

```txt
staff_rpl
```

---

# Submission Management

## Get All Submissions

Retrieve all applications that are currently managed by Staff RPL.

### Endpoint

```http
GET /api/staff/submissions
```

### Response

```json
{
    "success": true,
    "data": [
        {
            "id": 5,
            "applicant_id": 1,
            "assigned_assessor_id": null,
            "study_program_id": 2,
            "application_number": "RPL-2026-HJZEKN",
            "rpl_type": "hybrid",
            "status": "submitted",
            "review_notes": null,
            "revision_count": 0,
            "submitted_at": "2026-06-05T13:37:06.000000Z",
            "applicant": {},
            "study_program": {}
        }
    ]
}
```

### Notes

Returned statuses:

```txt
submitted
under_review
returned
```

---

## Get Submission Detail

Retrieve detailed information for a specific application.

### Endpoint

```http
GET /api/staff/submissions/{application}
```

### Example

```http
GET /api/staff/submissions/5
```

### Response

```json
{
    "success": true,
    "data": {
        "id": 5,
        "application_number": "RPL-2026-HJZEKN",
        "rpl_type": "hybrid",
        "status": "submitted",

        "applicant": {},

        "study_program": {},

        "a1_courses": [],

        "a2_learning_experiences": [],

        "documents": []
    }
}
```

### Included Relations

```txt
Applicant
User
Study Program
A1 Courses
A2 Learning Experiences
Documents
Assigned Assessor
```

---

# Review Submission

Move application status from:

```txt
submitted
↓
under_review
```

### Endpoint

```http
PATCH /api/staff/submissions/{application}/review
```

### Request Body

```json
{}
```

### Success Response

```json
{
    "success": true,
    "message": "Application review started.",
    "data": {
        "id": 5,
        "status": "under_review"
    }
}
```

### Validation Rules

Only applications with status:

```txt
submitted
```

can be reviewed.

---

# Return Application

Return application back to applicant for revision.

Move status from:

```txt
under_review
↓
returned
```

### Endpoint

```http
PATCH /api/staff/submissions/{application}/return
```

### Request Body

```json
{
    "review_notes": "Please upload a complete academic transcript."
}
```

### Success Response

```json
{
    "success": true,
    "message": "Application returned successfully.",
    "data": {
        "id": 5,
        "status": "returned",
        "review_notes": "Please upload a complete academic transcript.",
        "revision_count": 1
    }
}
```

### Validation Rules

Only applications with status:

```txt
under_review
```

can be returned.

### Required Fields

| Field        | Type   | Required |
| ------------ | ------ | -------- |
| review_notes | string | Yes      |

---

# Assign Assessor

Assign an assessor to an application.

Move status from:

```txt
under_review
↓
under_assessment
```

### Endpoint

```http
PATCH /api/staff/submissions/{application}/assign-assessor
```

### Request Body

```json
{
    "assessor_id": 4
}
```

### Success Response

```json
{
    "success": true,
    "message": "Assessor assigned successfully.",
    "data": {
        "id": 5,
        "assigned_assessor_id": 4,
        "status": "under_assessment",
        "assigned_assessor": {
            "id": 4,
            "name": "Spica",
            "email": "spica@grpl.com"
        }
    }
}
```

### Validation Rules

Only applications with status:

```txt
under_review
```

can be assigned to an assessor.

Selected user must have role:

```txt
assessor
```

### Required Fields

| Field       | Type    | Required |
| ----------- | ------- | -------- |
| assessor_id | integer | Yes      |

---

# Status Flow

## Staff RPL Workflow

```txt
submitted
↓
under_review
├── returned
└── under_assessment
```

### Description

| Status           | Description                                   |
| ---------------- | --------------------------------------------- |
| submitted        | Submitted by applicant and waiting for review |
| under_review     | Currently reviewed by Staff RPL               |
| returned         | Returned to applicant for revision            |
| under_assessment | Assigned to assessor and ready for assessment |

---

# Frontend Pages

## Submission List

```txt
/submissions
```

Display:

```txt
Application Number
Applicant
Study Program
RPL Type
Status
Submission Date
```

---

## Submission Detail

```txt
/submissions/{id}
```

Display:

```txt
Applicant Information
Application Information
A1 Courses
A2 Learning Experiences
Documents
Review Notes
Assigned Assessor
```

Available Actions:

```txt
Start Review
Return Application
Assign Assessor
```
