<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'test',
            'email' => 'test@com',
            'password' => Hash::make('test'),
            'profile' => 'pass:test'
        ]);

        DB::table('users')->insert([
            'name' => 'wanko',
            'email' => 'wanko@com',
            'password' => Hash::make('wanwan'),
            'profile' => 'pass:wanwan'
        ]);

        DB::table('users')->insert([
            'name' => 'goro',
            'email' => 'goro@com',
            'password' => Hash::make('goro'),
            'profile' => 'pass:goro'
        ]);
    }
}
