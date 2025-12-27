<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'], 
            [
                'name' => 'Super Admin',
                'password' => Hash::make('admin123'), 
                'is_admin' => true,
                'total_capacity' => 10737418240, 
                'used_capacity' => 0,
            ]
        );
    }
}
