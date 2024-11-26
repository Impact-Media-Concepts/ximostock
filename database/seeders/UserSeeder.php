<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\WorkSpace;
use Illuminate\Support\Facades\Hash;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::factory()->create([
            'role' => 'admin',
            'work_space_id' => null,
            'email' => 'admin@default.test',
            'password' => Hash::make('admin'),
            'remember_token' => '',
            'email_verified_at' => now(),
            'name' => 'Admin',
        ]);

        WorkSpace::factory()->create([
            'name' => 'DefaultWorkspace',
        ]);
    }
}
