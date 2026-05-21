# G-RPL2 Backend API Documentation

## Base URL

```txt
http://(your_ip_network):8000/api
```

---

# Required Headers

```http
Accept: application/json
Content-Type: application/json
```

Protected routes:

```http
Authorization: Bearer TOKEN
```

---

# Authentication Flow

```txt
Register
→ Verification Email
→ Verify Email
→ Login
→ Get Sanctum Token
→ Access Protected API
→ Logout
```

---

# 1. Register Applicant

## Endpoint

```http
POST /api/auth/register
```

## Request Body

```json
{
  "name": "Ren",
  "email": "ren@example.com",
  "password": "password123",
  "password_confirmation": "password123"
}
```

## Success Response

```json
{
  "success": true,
  "message": "Verification email sent successfully",
  "user": {
    "id": 1,
    "name": "Ren",
    "email": "ren@example.com",
    "status": "active"
  }
}
```

## Notes

* Applicant role assigned automatically
* Verification email required before login
* No token returned on register

---

# 2. Login

## Endpoint

```http
POST /api/auth/login
```

## Request Body

```json
{
  "email": "ren@example.com",
  "password": "password123"
}
```

## Success Response

```json
{
  "success": true,
  "message": "Login success",
  "token": "1|sanctum_token_here",
  "user": {
    "id": 1,
    "name": "Ren",
    "email": "ren@example.com"
  }
}
```

## Email Not Verified

```json
{
  "success": false,
  "message": "Email not verified"
}
```

---

# 3. Email Verification

## Endpoint

```http
GET /api/auth/email/verify/{id}/{hash}
```

## Success Response

```json
{
  "success": true,
  "message": "Email verified successfully"
}
```

---

# 4. Current User

## Endpoint

```http
GET /api/auth/me
```

## Middleware

```txt
auth:sanctum
```

## Success Response

```json
{
  "success": true,
  "data": {
    "id": 1,
    "name": "Ren",
    "email": "ren@example.com"
  }
}
```

---

# 5. Logout

## Endpoint

```http
POST /api/auth/logout
```

## Middleware

```txt
auth:sanctum
```

## Success Response

```json
{
  "success": true,
  "message": "Logout success"
}
```

---

# Rate Limit

| Endpoint    | Limit     |
| ----------- | --------- |
| Login       | 5/minute  |
| Register    | 3/minute  |
| General API | 60/minute |

---

# Queue Worker

```bash
php artisan queue:work
```

---