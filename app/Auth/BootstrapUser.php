<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\Authenticatable;

class BootstrapUser implements Authenticatable
{
    protected array $attributes;

    public function __construct(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * Get the name of the unique identifier for the user.
     *
     * @return string
     */
    public function getAuthIdentifierName()
    {
        return 'email';
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->attributes['email'] ?? null;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        // Priority 1: Check DB-stored password hash (changed via Site Settings)
        try {
            $dbHash = \App\Models\SiteSetting::getValue('admin_password_hash');
            if ($dbHash) {
                return $dbHash;
            }
        } catch (\Exception $e) {
            // DB not available, fall through to env-based hash
        }

        // Priority 2: Environment variable hash
        return env('BOOTSTRAP_ADMIN_PASSWORD_HASH');
    }

    /**
     * Get the name of the password attribute for the user.
     *
     * @return string
     */
    public function getAuthPasswordName(): string
    {
        return 'password';
    }

    /**
     * Get the token value for the "remember me" session.
     *
     * @return string|null
     */
    public function getRememberToken()
    {
        return null;
    }

    /**
     * Set the token value for the "remember me" session.
     *
     * @param  string  $value
     * @return void
     */
    public function setRememberToken($value)
    {
        // Not supported in bootstrap mode
    }

    /**
     * Get the column name for the "remember me" token.
     *
     * @return string
     */
    public function getRememberTokenName()
    {
        return '';
    }

    /**
     * Get user attribute
     *
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * Set user attribute
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->attributes[$key] = $value;
    }

    /**
     * Check if attribute exists
     *
     * @param string $key
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->attributes[$key]);
    }

    /**
     * Get all attributes
     *
     * @return array
     */
    public function toArray()
    {
        return $this->attributes;
    }

    /**
     * Check if user is admin
     *
     * @return bool
     */
    public function isAdmin(): bool
    {
        return ($this->attributes['role'] ?? null) === 'admin';
    }
}

