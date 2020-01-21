<?php

use Illuminate\Database\Seeder;

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
            'name' => "tester test",
            'email' => 'tester@test.com',
            'password' => bcrypt('password'),
        ]);
    }
}
