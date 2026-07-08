<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   */
  public function run(): void
  {
    // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin',
            'username' => 'admin',
            'email' => 'admin@example.com',
            'phone' => '01717171717',
            'address' => '1234567890',
            'city' => 'Dhaka',
            'state' => 'Dhaka',
            'zip' => '1234567890',
            'country' => 'Bangladesh',
            'role' => 'admin',
            'status' => 'active',
            'latitude' => '23.71836',
            'longitude' => '90.354222',
            

            'password' => Hash::make('password'),
        ]);

        $this->call(PaymentMethodSeeder::class);
    }
}