<?php

use Illuminate\Database\Seeder;
use App\models\Role;
class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $hospital_director = Role::create([
            'name' => 'Giám đốc viện', 
            'slug' => 'director',
            'permission' => [
                'urgent_reports.index' => true,
                'urgent_reports.show' => true,
            ]
        ]);
        $urgent = Role::create([
            'name' => 'Khoa cấp cứu', 
            'slug' => 'urgent',
            'permission' => [
                'urgent_reports.index' => true,
                'urgent_reports.show' => true,
                'urgent_reports.create' => true,
                'complains.index' => true,
                'complains.show' => true,
                'complains.create' => true,
            ]
        ]);
        $general= Role::create([ 
            'name' => 'Phòng tổng hợp', 
            'slug' => 'general',
            'permission' => [
                'urgent_reports.index' => true,
                'urgent_reports.show' => true, 
                'urgent_reports.create' => true, 
                'complains.index' => true,
                'complains.show' => true,
                'complains.create' => true,
            ]
        ]);
    }
}
