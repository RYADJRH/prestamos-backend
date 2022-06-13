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
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'name_user'         => 'Rafael',
            'last_name_user'    => 'Rebolledo Rendon',
            'nick_name_user'    => 'rebolledo',
            'password_user'     => Hash::make('R162608P')
        ]);

        User::create([
            'name_user'         => 'admin',
            'last_name_user'    => 'admin admin',
            'nick_name_user'    => 'admin',
            'password_user'     => Hash::make('admin_password')
        ]);
    }
}
