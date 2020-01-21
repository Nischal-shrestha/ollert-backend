<?php

use Illuminate\Database\Seeder;

class BoardsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('boards')->insert([
            'name' => "Board Test One",
            'description' => 'Testing description for board test one',
            'visibility' => 'PUBLIC',
            'background' => '#000000',
            'owner_id' => 1,
        ]);

        DB::table('board_user')->insert([
            'user_id' => 1,
            'board_id' => 1,
            'is_owner' => 1,
        ]);
    }
}
