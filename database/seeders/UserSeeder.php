<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['phone' => '0987654321'],
            ['name' => 'user1', 'password' => 123]
        )->assignRole('user');

        User::updateOrCreate(
            ['phone' => '0987654322'],
            ['name' => 'user2', 'password' => 123]
        )->assignRole('user');

        User::factory(7)->create()->each->assignRole('user');
    }
}
