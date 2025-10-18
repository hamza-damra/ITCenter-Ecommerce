# Laravel Authentication System - Implementation Guide

## Overview
This project implements a **complete authentication system** using Laravel's built-in features and **Laravel Sanctum** for API token authentication, following best practices as recommended by Laravel documentation.

## Architecture

### 1. Hybrid Authentication Approach
- **Web Authentication**: Session-based authentication using Laravel's built-in `Auth` facade
- **API Authentication**: Token-based authentication using Laravel Sanctum
- **Dual Controller Pattern**: 
  - `AuthController` for web views
  - `Api\AuthController` for API endpoints

### 2. Database Structure

#### Users Table
```sql
- id (primary key)
- name (full name)
- first_name
- last_name
- email (unique)
- phone
- email_verified_at
- password (hashed)
- remember_token
- timestamps
```

#### Password Reset Tokens Table
```sql
- email (primary key)
- token
- created_at
```

#### Personal Access Tokens Table (Sanctum)
```sql
- id
- tokenable_type
- tokenable_id
- name
- token (hashed)
- abilities
- last_used_at
- expires_at
- timestamps
```

## API Endpoints

### Base URL: `/api/v1/auth`

### Public Endpoints

#### 1. Register
```http
POST /api/v1/auth/register
Content-Type: application/json

{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "password": "SecurePassword123",
    "password_confirmation": "SecurePassword123"
}
```

**Response (201 Created):**
```json
{
    "success": true,
    "message": "Registration successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com",
            "phone": "+1234567890",
            "created_at": "2025-01-01T00:00:00.000000Z"
        },
        "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "token_type": "Bearer"
    }
}
```

#### 2. Login
```http
POST /api/v1/auth/login
Content-Type: application/json

{
    "email": "john@example.com",
    "password": "SecurePassword123",
    "device_name": "iPhone 15"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Login successful",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com",
            "phone": "+1234567890",
            "email_verified_at": null
        },
        "token": "2|xxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "token_type": "Bearer"
    }
}
```

#### 3. Forgot Password
```http
POST /api/v1/auth/forgot-password
Content-Type: application/json

{
    "email": "john@example.com"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Password reset link sent to your email",
    "data": null
}
```

#### 4. Reset Password
```http
POST /api/v1/auth/reset-password
Content-Type: application/json

{
    "token": "reset_token_from_email",
    "email": "john@example.com",
    "password": "NewSecurePassword123",
    "password_confirmation": "NewSecurePassword123"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Password reset successfully",
    "data": null
}
```

### Protected Endpoints (Require Authentication)

**Add header:** `Authorization: Bearer {token}`

#### 5. Get Current User
```http
GET /api/v1/auth/me
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "User data retrieved successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "first_name": "John",
            "last_name": "Doe",
            "email": "john@example.com",
            "phone": "+1234567890",
            "email_verified_at": null,
            "created_at": "2025-01-01T00:00:00.000000Z"
        }
    }
}
```

#### 6. Logout (Revoke Current Token)
```http
POST /api/v1/auth/logout
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Logout successful",
    "data": null
}
```

#### 7. Logout All Devices
```http
POST /api/v1/auth/logout-all
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Logged out from all devices",
    "data": null
}
```

#### 8. Refresh Token
```http
POST /api/v1/auth/refresh
Authorization: Bearer {token}
Content-Type: application/json

{
    "device_name": "iPhone 15"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Token refreshed successfully",
    "data": {
        "token": "3|xxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "token_type": "Bearer"
    }
}
```

#### 9. Get Active Sessions
```http
GET /api/v1/auth/sessions
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Active sessions retrieved successfully",
    "data": {
        "sessions": [
            {
                "id": 1,
                "name": "web-browser",
                "last_used_at": "2025-01-01T12:00:00.000000Z",
                "created_at": "2025-01-01T10:00:00.000000Z",
                "is_current": true
            },
            {
                "id": 2,
                "name": "iPhone 15",
                "last_used_at": "2025-01-01T11:00:00.000000Z",
                "created_at": "2025-01-01T09:00:00.000000Z",
                "is_current": false
            }
        ]
    }
}
```

#### 10. Revoke Specific Session
```http
DELETE /api/v1/auth/sessions/{tokenId}
Authorization: Bearer {token}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Session revoked successfully",
    "data": null
}
```

#### 11. Change Password
```http
POST /api/v1/auth/change-password
Authorization: Bearer {token}
Content-Type: application/json

{
    "current_password": "OldPassword123",
    "password": "NewPassword123",
    "password_confirmation": "NewPassword123"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Password changed successfully",
    "data": null
}
```

#### 12. Update Profile
```http
PUT /api/v1/auth/profile
Authorization: Bearer {token}
Content-Type: application/json

{
    "first_name": "Jane",
    "last_name": "Smith",
    "phone": "+9876543210",
    "email": "jane@example.com"
}
```

**Response (200 OK):**
```json
{
    "success": true,
    "message": "Profile updated successfully",
    "data": {
        "user": {
            "id": 1,
            "name": "Jane Smith",
            "first_name": "Jane",
            "last_name": "Smith",
            "email": "jane@example.com",
            "phone": "+9876543210"
        }
    }
}
```

## Web Routes

### Authentication Pages

1. **Login**: `GET /login`
2. **Login Action**: `POST /login`
3. **Register**: `GET /register`
4. **Register Action**: `POST /register`
5. **Logout**: `POST /logout` (requires authentication)
6. **Forgot Password**: `GET /forgot-password`
7. **Forgot Password Action**: `POST /forgot-password`
8. **Reset Password**: `GET /reset-password/{token}`
9. **Reset Password Action**: `POST /reset-password`

## Setup Instructions

### 1. Run Migrations
```bash
php artisan migrate:fresh
```

### 2. Configure Mail Settings
Update `.env` file:
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@itcenter.com
MAIL_FROM_NAME="${APP_NAME}"
```

### 3. Configure Sanctum (Optional)
For SPA authentication, update `.env`:
```env
SANCTUM_STATEFUL_DOMAINS=localhost,localhost:3000,127.0.0.1,127.0.0.1:8000
SESSION_DOMAIN=localhost
```

### 4. Configure Frontend URL (For Password Reset Links)
Update `config/app.php` or `.env`:
```env
APP_FRONTEND_URL=http://localhost:3000
```

## Testing with Postman/cURL

### Register Example
```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "first_name": "Test",
    "last_name": "User",
    "email": "test@example.com",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### Login Example
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'
```

### Get User Profile Example
```bash
curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE"
```

## Security Features

1. **Password Hashing**: Bcrypt hashing (Laravel default)
2. **CSRF Protection**: For web routes (session-based)
3. **Rate Limiting**: Built-in throttling for authentication attempts
4. **Token Management**: Sanctum handles token storage and validation
5. **Password Reset**: Secure token-based password reset
6. **Session Management**: Multiple device/session support
7. **Token Expiration**: Configurable token expiration (default: never)

## Multi-Language Support

All error messages and responses support the project's multi-language system:
- Arabic (ar) - Default
- English (en)
- Hebrew (he)

Use `__t('key')` helper for translations.

## Error Responses

All API errors follow this structure:

```json
{
    "success": false,
    "message": "Error message here",
    "errors": {
        "field_name": ["Error description"]
    }
}
```

### Common HTTP Status Codes
- `200 OK`: Successful request
- `201 Created`: Resource created successfully
- `400 Bad Request`: Validation error or bad input
- `401 Unauthorized`: Invalid credentials or missing token
- `404 Not Found`: Resource not found
- `500 Internal Server Error`: Server error

## Best Practices Implemented

1. ✅ **Laravel Sanctum** for API authentication
2. ✅ **Session-based authentication** for web
3. ✅ **Password reset** with email notifications
4. ✅ **Token refresh** mechanism
5. ✅ **Multi-device session management**
6. ✅ **Validation** at controller level
7. ✅ **ApiResponses trait** for consistent API responses
8. ✅ **Separation of concerns** (Web vs API controllers)
9. ✅ **Secure password storage** (Bcrypt)
10. ✅ **Remember me** functionality for web
11. ✅ **Custom password reset notifications**
12. ✅ **Profile management**

## Next Steps

1. ✅ **Email Verification**: Implement email verification for new users
2. ✅ **Two-Factor Authentication (2FA)**: Add optional 2FA
3. ✅ **Social Login**: Integrate OAuth providers (Google, Facebook, etc.)
4. ✅ **Role-Based Access Control (RBAC)**: Add user roles and permissions
5. ✅ **Activity Logging**: Track user login history
6. ✅ **Rate Limiting**: Custom rate limiting for API endpoints

## References

- [Laravel Authentication Documentation](https://laravel.com/docs/11.x/authentication)
- [Laravel Sanctum Documentation](https://laravel.com/docs/11.x/sanctum)
- [Laravel Password Reset](https://laravel.com/docs/11.x/passwords)
