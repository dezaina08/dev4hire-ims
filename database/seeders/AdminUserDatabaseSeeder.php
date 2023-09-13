<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Ramsey\Uuid\Type\Integer;

class AdminUserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!User::find(1)) {

            $admin = User::firstOrCreate([
                "name" => "Admin",
                "username" => "admin",
                "email" => "admin@test.com",
                "email_verified_at" => now(),
                "password" => Hash::make("pw@12345"),
                "remember_token" => Str::random(10),
            ]);

            $admin->assignRole('Admin');
        }
    }
}
