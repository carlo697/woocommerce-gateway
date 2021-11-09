<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('stores')->insert([
            'name' => 'valencia',
            'wc_id' => "1",
            'created_at' => Carbon::now()
        ]);

        DB::table('stores')->insert([
            'name' => 'barquisimeto',
            'wc_id' => "2",
            'created_at' => Carbon::now()
        ]);
        DB::table('stores')->insert([
            'name' => 'lara',
            'wc_id' => "3",
            'created_at' => Carbon::now()
        ]);
    }
}
