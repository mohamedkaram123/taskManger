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
        //
        $user = new User();
        $user->first_name = "mohamed";
        $user->last_name = "karam";
        $user->email = "admin@admin.com";
        $user->user_type = "admin";
        $user->password = Hash::make("admin") ;
        $user->save();
    }
}
