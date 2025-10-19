<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 sample users
        $users = [
            ['first_name' => 'John', 'last_name' => 'Doe', 'email' => 'john@test.com'],
            ['first_name' => 'Jane', 'last_name' => 'Smith', 'email' => 'jane@test.com'],
            ['first_name' => 'Michael', 'last_name' => 'Johnson', 'email' => 'michael@test.com'],
            ['first_name' => 'Sarah', 'last_name' => 'Williams', 'email' => 'sarah@test.com'],
            ['first_name' => 'David', 'last_name' => 'Brown', 'email' => 'david@test.com'],
            ['first_name' => 'Emily', 'last_name' => 'Davis', 'email' => 'emily@test.com'],
            ['first_name' => 'James', 'last_name' => 'Miller', 'email' => 'james@test.com'],
            ['first_name' => 'Emma', 'last_name' => 'Wilson', 'email' => 'emma@test.com'],
            ['first_name' => 'Robert', 'last_name' => 'Moore', 'email' => 'robert@test.com'],
            ['first_name' => 'Olivia', 'last_name' => 'Taylor', 'email' => 'olivia@test.com'],
        ];

        foreach ($users as $userData) {
            User::create([
                'name' => $userData['first_name'] . ' ' . $userData['last_name'],
                'first_name' => $userData['first_name'],
                'last_name' => $userData['last_name'],
                'email' => $userData['email'],
                'password' => Hash::make('password'), // Default password: password
                'phone' => '+970' . rand(500000000, 599999999),
                'role' => 'customer',
            ]);

            $this->command->info("Created user: {$userData['email']}");
        }

        $this->command->info('✓ Sample users created successfully!');
    }
}
