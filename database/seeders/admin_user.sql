-- Insert Admin User
-- Email: admin@itcenter.com
-- Password: admin123

INSERT INTO users (name, first_name, last_name, email, password, role, email_verified_at, created_at, updated_at)
SELECT * FROM (
    SELECT
        'Admin' as name,
        'Admin' as first_name,
        'User' as last_name,
        'admin@itcenter.com' as email,
        '$2y$12$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi' as password, -- admin123
        'admin' as role,
        NOW() as email_verified_at,
        NOW() as created_at,
        NOW() as updated_at
) AS tmp
WHERE NOT EXISTS (
    SELECT email FROM users WHERE email = 'admin@itcenter.com'
) LIMIT 1;
