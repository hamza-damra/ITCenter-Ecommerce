# API Authentication Testing Script

## Using PowerShell (Windows)

### 1. Register New User
```powershell
$body = @{
    first_name = "John"
    last_name = "Doe"
    email = "john.doe@example.com"
    phone = "+1234567890"
    password = "password123"
    password_confirmation = "password123"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/register" `
    -Method Post `
    -Headers @{"Content-Type"="application/json"; "Accept"="application/json"} `
    -Body $body
```

### 2. Login
```powershell
$body = @{
    email = "john.doe@example.com"
    password = "password123"
    device_name = "PowerShell Test"
} | ConvertTo-Json

$response = Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/login" `
    -Method Post `
    -Headers @{"Content-Type"="application/json"; "Accept"="application/json"} `
    -Body $body

# Save token for later use
$token = $response.data.token
Write-Host "Token: $token"
```

### 3. Get Current User Profile
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/me" `
    -Method Get `
    -Headers @{
        "Accept"="application/json"
        "Authorization"="Bearer $token"
    }
```

### 4. Update Profile
```powershell
$body = @{
    first_name = "Jane"
    last_name = "Smith"
    phone = "+9876543210"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/profile" `
    -Method Put `
    -Headers @{
        "Content-Type"="application/json"
        "Accept"="application/json"
        "Authorization"="Bearer $token"
    } `
    -Body $body
```

### 5. Change Password
```powershell
$body = @{
    current_password = "password123"
    password = "newpassword456"
    password_confirmation = "newpassword456"
} | ConvertTo-Json

Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/change-password" `
    -Method Post `
    -Headers @{
        "Content-Type"="application/json"
        "Accept"="application/json"
        "Authorization"="Bearer $token"
    } `
    -Body $body
```

### 6. Get Active Sessions
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/sessions" `
    -Method Get `
    -Headers @{
        "Accept"="application/json"
        "Authorization"="Bearer $token"
    }
```

### 7. Logout
```powershell
Invoke-RestMethod -Uri "http://localhost:8000/api/v1/auth/logout" `
    -Method Post `
    -Headers @{
        "Accept"="application/json"
        "Authorization"="Bearer $token"
    }
```

---

## Using cURL (Linux/Mac/Git Bash)

### 1. Register New User
```bash
curl -X POST http://localhost:8000/api/v1/auth/register \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "first_name": "John",
    "last_name": "Doe",
    "email": "john.doe@example.com",
    "phone": "+1234567890",
    "password": "password123",
    "password_confirmation": "password123"
  }'
```

### 2. Login
```bash
curl -X POST http://localhost:8000/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "john.doe@example.com",
    "password": "password123",
    "device_name": "cURL Test"
  }'

# Save the token from response
TOKEN="paste_your_token_here"
```

### 3. Get Current User Profile
```bash
curl -X GET http://localhost:8000/api/v1/auth/me \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

### 4. Update Profile
```bash
curl -X PUT http://localhost:8000/api/v1/auth/profile \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "first_name": "Jane",
    "last_name": "Smith",
    "phone": "+9876543210"
  }'
```

### 5. Change Password
```bash
curl -X POST http://localhost:8000/api/v1/auth/change-password \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "current_password": "password123",
    "password": "newpassword456",
    "password_confirmation": "newpassword456"
  }'
```

### 6. Get Active Sessions
```bash
curl -X GET http://localhost:8000/api/v1/auth/sessions \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

### 7. Logout
```bash
curl -X POST http://localhost:8000/api/v1/auth/logout \
  -H "Accept: application/json" \
  -H "Authorization: Bearer $TOKEN"
```

---

## Using JavaScript (Fetch API)

```javascript
const BASE_URL = 'http://localhost:8000/api/v1/auth';
let authToken = '';

// 1. Register
async function register() {
    const response = await fetch(`${BASE_URL}/register`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            first_name: 'John',
            last_name: 'Doe',
            email: 'john.doe@example.com',
            phone: '+1234567890',
            password: 'password123',
            password_confirmation: 'password123'
        })
    });
    const data = await response.json();
    console.log('Register:', data);
    authToken = data.data.token;
    return data;
}

// 2. Login
async function login() {
    const response = await fetch(`${BASE_URL}/login`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            email: 'john.doe@example.com',
            password: 'password123',
            device_name: 'Browser'
        })
    });
    const data = await response.json();
    console.log('Login:', data);
    authToken = data.data.token;
    return data;
}

// 3. Get Profile
async function getProfile() {
    const response = await fetch(`${BASE_URL}/me`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${authToken}`
        }
    });
    const data = await response.json();
    console.log('Profile:', data);
    return data;
}

// 4. Update Profile
async function updateProfile() {
    const response = await fetch(`${BASE_URL}/profile`, {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${authToken}`
        },
        body: JSON.stringify({
            first_name: 'Jane',
            last_name: 'Smith',
            phone: '+9876543210'
        })
    });
    const data = await response.json();
    console.log('Update Profile:', data);
    return data;
}

// 5. Change Password
async function changePassword() {
    const response = await fetch(`${BASE_URL}/change-password`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': `Bearer ${authToken}`
        },
        body: JSON.stringify({
            current_password: 'password123',
            password: 'newpassword456',
            password_confirmation: 'newpassword456'
        })
    });
    const data = await response.json();
    console.log('Change Password:', data);
    return data;
}

// 6. Get Sessions
async function getSessions() {
    const response = await fetch(`${BASE_URL}/sessions`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${authToken}`
        }
    });
    const data = await response.json();
    console.log('Sessions:', data);
    return data;
}

// 7. Logout
async function logout() {
    const response = await fetch(`${BASE_URL}/logout`, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Authorization': `Bearer ${authToken}`
        }
    });
    const data = await response.json();
    console.log('Logout:', data);
    authToken = '';
    return data;
}

// Run tests
(async () => {
    try {
        await register();
        await login();
        await getProfile();
        await updateProfile();
        await getSessions();
        await logout();
    } catch (error) {
        console.error('Error:', error);
    }
})();
```

---

## Using Python (requests library)

```python
import requests

BASE_URL = 'http://localhost:8000/api/v1/auth'
auth_token = ''

# 1. Register
def register():
    response = requests.post(f'{BASE_URL}/register', 
        headers={
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        json={
            'first_name': 'John',
            'last_name': 'Doe',
            'email': 'john.doe@example.com',
            'phone': '+1234567890',
            'password': 'password123',
            'password_confirmation': 'password123'
        }
    )
    data = response.json()
    print('Register:', data)
    global auth_token
    auth_token = data['data']['token']
    return data

# 2. Login
def login():
    response = requests.post(f'{BASE_URL}/login',
        headers={
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        json={
            'email': 'john.doe@example.com',
            'password': 'password123',
            'device_name': 'Python Script'
        }
    )
    data = response.json()
    print('Login:', data)
    global auth_token
    auth_token = data['data']['token']
    return data

# 3. Get Profile
def get_profile():
    response = requests.get(f'{BASE_URL}/me',
        headers={
            'Accept': 'application/json',
            'Authorization': f'Bearer {auth_token}'
        }
    )
    data = response.json()
    print('Profile:', data)
    return data

# 4. Update Profile
def update_profile():
    response = requests.put(f'{BASE_URL}/profile',
        headers={
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'Authorization': f'Bearer {auth_token}'
        },
        json={
            'first_name': 'Jane',
            'last_name': 'Smith',
            'phone': '+9876543210'
        }
    )
    data = response.json()
    print('Update Profile:', data)
    return data

# 5. Logout
def logout():
    response = requests.post(f'{BASE_URL}/logout',
        headers={
            'Accept': 'application/json',
            'Authorization': f'Bearer {auth_token}'
        }
    )
    data = response.json()
    print('Logout:', data)
    return data

# Run tests
if __name__ == '__main__':
    register()
    login()
    get_profile()
    update_profile()
    logout()
```

---

## Expected Responses

### Successful Registration/Login
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
      "email": "john.doe@example.com",
      "phone": "+1234567890",
      "created_at": "2025-10-18T12:00:00.000000Z"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "token_type": "Bearer"
  }
}
```

### Error Response
```json
{
  "success": false,
  "message": "The provided credentials are incorrect.",
  "errors": {
    "email": ["The email field is required."]
  }
}
```

---

## Tips for Testing

1. **Save Your Token**: After login, save the token to use in subsequent requests
2. **Use Environment Variables**: Store base URL and token for easier testing
3. **Check Response Status**: 
   - `200` = Success
   - `201` = Created
   - `400` = Bad Request
   - `401` = Unauthorized
   - `500` = Server Error
4. **Test Error Cases**: Try invalid emails, wrong passwords, missing fields
5. **Check Database**: Verify data is being saved correctly

---

## Troubleshooting

### Error: "Token Mismatch"
- Clear cache: `php artisan config:clear`
- Restart server

### Error: "Route not found"
- Check base URL is correct
- Ensure server is running: `php artisan serve`

### Error: "Unauthenticated"
- Verify token is included in Authorization header
- Check token format: `Bearer {token}`
- Token may have been revoked - login again
