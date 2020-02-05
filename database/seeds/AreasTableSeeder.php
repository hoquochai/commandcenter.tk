<?php

use Illuminate\Database\Seeder;

class AreasTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('areas')->delete();
        DB::table('areas')->insert(
            [
                ['id'=>1, "name"=>"Nhà A1"],
                ['id'=>2, "name"=>"Nhà A2"],
                ['id'=>3, "name"=>"Nhà A3"],
                ['id'=>4, "name"=>"Nhà A4"],
                ['id'=>5, "name"=>"Nhà A5"],
            ]
        );
    }
}
