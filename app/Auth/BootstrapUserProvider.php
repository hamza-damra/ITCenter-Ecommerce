<?php

namespace App\Auth;

use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class BootstrapUserProvider implements UserProvider
{
    /**
     * Retrieve a user by their unique identifier.
     *
     * @param  mixed  $identifier
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveById($identifier)
    {
        // In bootstrap mode, we only support one admin user
        // Check if identifier matches bootstrap admin email
        $bootstrapEmail = env('BOOTSTRAP_ADMIN_EMAIL');
        
        if ($bootstrapEmail && $identifier === $bootstrapEmail) {
            return $this->getBootstrapUser();
        }

        return null;
    }

    /**
     * Retrieve a user by their unique identifier and "remember me" token.
     *
     * @param  mixed  $identifier
     * @param  string  $token
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByToken($identifier, $token)
    {
        // Bootstrap mode doesn't support remember me tokens
        return null;
    }

    /**
     * Update the "remember me" token for the given user in storage.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $token
     * @return void
     */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // Bootstrap mode doesn't support remember me tokens
    }

    /**
     * Retrieve a user by the given credentials.
     *
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        $email = $credentials['email'] ?? null;
        $bootstrapEmail = env('BOOTSTRAP_ADMIN_EMAIL');

        if (!$email || $email !== $bootstrapEmail) {
            return null;
        }

        return $this->getBootstrapUser();
    }

    /**
     * Validate a user against the given credentials.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @return bool
     */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        $password = $credentials['password'] ?? null;
        
        if (!$password) {
            return false;
        }

        // Priority 1: Check DB-stored password hash (from Site Settings)
        try {
            $dbHash = \App\Models\SiteSetting::getValue('admin_password_hash');
            if ($dbHash && Hash::check($password, $dbHash)) {
                return true;
            }
        } catch (\Exception $e) {
            // DB not available, fall through to env-based check
        }

        // Priority 2: Check environment variable hash
        $passwordHash = env('BOOTSTRAP_ADMIN_PASSWORD_HASH');

        if (!$passwordHash) {
            Log::error('BOOTSTRAP_ADMIN_PASSWORD_HASH not set in environment');
            return false;
        }

        // Verify password
        if (Hash::check($password, $passwordHash)) {
            return true;
        }

        // Also check if it's a plain bcrypt hash (for initial setup)
        if (password_verify($password, $passwordHash)) {
            return true;
        }

        return false;
    }

    /**
     * Rehash the user's password if required and the given value passes.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  array  $credentials
     * @param  bool  $force
     * @return void
     */
    public function rehashPasswordIfRequired(Authenticatable $user, array $credentials, bool $force = false): void
    {
        // In bootstrap mode, password is stored in environment variable
        // We don't rehash it automatically as it's managed externally
        // This method is required by Laravel 12+ but we don't need to do anything
    }

    /**
     * Get the bootstrap user instance
     *
     * @return BootstrapUser
     */
    protected function getBootstrapUser(): BootstrapUser
    {
        return new BootstrapUser([
            'id' => 1,
            'email' => env('BOOTSTRAP_ADMIN_EMAIL', 'admin@example.com'),
            'name' => 'Bootstrap Admin',
            'role' => 'admin',
        ]);
    }
}

