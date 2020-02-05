<?php

use Illuminate\Database\Seeder;

class AccountTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('account_types')->delete();
        DB::table('account_types')->insert(
            [
                // ['id'=>1, "name"=>"Bệnh viện", 'code'=>"BV"],
                // ['id'=>2, "name"=>"Sở Y Tế", 'code'=>"SYT"],
                // ['id'=>3, "name"=>"Bộ Y Tế", 'code'=>'BYT'],
                ['id'=>1 , 'name' => 'Giám đốc bệnh viên', 'code' => "GD"],
                ['id'=>2 , 'name' => 'Khoa cấp cứu', 'code' => "CC"],
                ['id'=>3 , 'name' => 'Phòng tổng hợp', 'code' => "TH"],
            ]
        );
    }
}
