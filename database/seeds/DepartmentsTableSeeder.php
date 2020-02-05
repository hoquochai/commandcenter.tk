<?php

use Illuminate\Database\Seeder;

class DepartmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->delete();
        DB::table('departments')->insert(
            [
                ['id'=>1, "name"=>"Ban giám đốc", 'hospitals_id'=>15, 'areas_id'=>1],
                ['id'=>2, "name"=>"Khoa Điều dưỡng", 'hospitals_id'=>15, 'areas_id'=>2],
                ['id'=>3, "name"=>"Khoa Sản", 'hospitals_id'=>15, 'areas_id'=>2],
                ['id'=>4, "name"=>"Khoa cấp cứu", 'hospitals_id'=>15, 'areas_id'=>3],
                ['id'=>5, "name"=>"Phòng tổng hợp", 'hospitals_id'=>15, 'areas_id'=>3],
            ]
        );
    }
}
