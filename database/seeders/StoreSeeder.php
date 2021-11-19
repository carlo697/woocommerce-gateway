<?php

namespace Database\Seeders;

use Illuminate\Support\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

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

        DB::table('users')->insert([
            'name' => 'kervis vasquez',
            'email' => 'kervisvasquez24@gmail.com',
            'password' =>Hash::make("123456789"),
            'created_at' => Carbon::now()
        ]);
    }
}
