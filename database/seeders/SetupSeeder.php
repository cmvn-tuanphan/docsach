<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Author;
use Hash;
class SetupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::insert([
            'name' => 'admin',
        ]);
        Role::insert([
            'name' => 'user',
        ]);
        User::insert([
            'name' => 'Admin User',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('123456')
        ]);
        UserRole::updateOrCreate(
            ['user_id' => 1],
            ['role_id' => 1]
        );
        Author::insert([
            "author_name" => 1
        ]);
    }
}
