<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class SetupBootstrapMode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bootstrap:setup
                            {--email= : Bootstrap admin email}
                            {--password= : Bootstrap admin password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup bootstrap mode credentials';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Bootstrap Mode Setup');
        $this->info('===================');
        $this->newLine();

        // Get email
        $email = $this->option('email');
        if (!$email) {
            $email = $this->ask('Enter bootstrap admin email', 'admin@example.com');
        }

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error('Invalid email address.');
            return 1;
        }

        // Get password
        $password = $this->option('password');
        if (!$password) {
            $password = $this->secret('Enter bootstrap admin password');
            $passwordConfirm = $this->secret('Confirm password');
            
            if ($password !== $passwordConfirm) {
                $this->error('Passwords do not match.');
                return 1;
            }
        }

        // Generate password hash
        $passwordHash = Hash::make($password);

        // Display configuration
        $this->newLine();
        $this->info('Add these lines to your .env file:');
        $this->newLine();
        $this->line('BOOTSTRAP_MODE_ENABLED=true');
        $this->line("BOOTSTRAP_ADMIN_EMAIL={$email}");
        $this->line("BOOTSTRAP_ADMIN_PASSWORD_HASH={$passwordHash}");
        $this->newLine();

        // Ask if they want to update .env automatically
        if ($this->confirm('Do you want to update .env file automatically?', true)) {
            $envPath = base_path('.env');
            
            if (!file_exists($envPath)) {
                $this->error('.env file not found. Please create it first.');
                return 1;
            }

            $envContent = file_get_contents($envPath);

            // Update or add BOOTSTRAP_MODE_ENABLED
            if (preg_match('/^BOOTSTRAP_MODE_ENABLED=.*$/m', $envContent)) {
                $envContent = preg_replace('/^BOOTSTRAP_MODE_ENABLED=.*$/m', 'BOOTSTRAP_MODE_ENABLED=true', $envContent);
            } else {
                $envContent .= "\nBOOTSTRAP_MODE_ENABLED=true\n";
            }

            // Update or add BOOTSTRAP_ADMIN_EMAIL
            if (preg_match('/^BOOTSTRAP_ADMIN_EMAIL=.*$/m', $envContent)) {
                $envContent = preg_replace('/^BOOTSTRAP_ADMIN_EMAIL=.*$/m', "BOOTSTRAP_ADMIN_EMAIL={$email}", $envContent);
            } else {
                $envContent .= "BOOTSTRAP_ADMIN_EMAIL={$email}\n";
            }

            // Update or add BOOTSTRAP_ADMIN_PASSWORD_HASH
            if (preg_match('/^BOOTSTRAP_ADMIN_PASSWORD_HASH=.*$/m', $envContent)) {
                $envContent = preg_replace('/^BOOTSTRAP_ADMIN_PASSWORD_HASH=.*$/m', "BOOTSTRAP_ADMIN_PASSWORD_HASH={$passwordHash}", $envContent);
            } else {
                $envContent .= "BOOTSTRAP_ADMIN_PASSWORD_HASH={$passwordHash}\n";
            }

            file_put_contents($envPath, $envContent);

            $this->info('✓ .env file updated successfully!');
            $this->newLine();
            $this->warn('Remember to clear config cache: php artisan config:clear');
        }

        $this->newLine();
        $this->info('Setup complete! Bootstrap mode is ready to use.');
        $this->info('When database is missing, visit /admin to access bootstrap mode.');

        return 0;
    }
}

