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
        User::create([
            'role_id' => 1,
            'department_id' => 1,
            'employee_number' => '9667',
            'name' => 'test',
            'email' => 'test@example.test',
            'password' => Hash::make('12345678'),
            
        ]);
    }
}
