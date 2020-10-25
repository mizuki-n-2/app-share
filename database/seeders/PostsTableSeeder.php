<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = Carbon::now();

        for($i = 1; $i <= 3; $i++) {
            DB::table('posts')->insert([
                'user_id' => $i,
                'title' => 'app'.$i,
                'content' => 'app'.$i.'です',
                'created_at' => $now
            ]);
        }
    }
}
