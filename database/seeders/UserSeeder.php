<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $defaultUsers = [
            ['name' => 'Alice', 'email' => 'alice@example.com', 'password' => bcrypt('default123')],
            ['name' => 'Bob', 'email' => 'bob@example.com', 'password' => bcrypt('default123')],
        ];

        foreach ($defaultUsers as $u) {
            User::firstOrCreate(['email' => $u['email']], $u);
        }
    }
}
