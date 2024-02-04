<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /// Using DB facade
        DB::table('users')->insert([
            'firstName' => 'John',
            'lastName' => 'Doe',
            'email' => 'admin@gmail.com',
            'mobile' => '01609345280',
            'role'=>'admin',
            'password' => Hash::make('admin'),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
//php artisan db:seed --class=UsersTableSeeder

