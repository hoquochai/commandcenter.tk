<?php

use Illuminate\Database\Seeder;
use App\User;
use App\models\Role;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->delete();
        $director = Role::where('slug', 'director')->first();
        $urgent = Role::where('slug', 'urgent')->first();
        $general = Role::where('slug', 'general')->first();

        $user1 = User::create( ['id'=>3, "name"=>"Khoa cấp cứu",'image'=>'public\uploads\avatar.jpg','parent_id'=>1, "email"=>"nguyenkhachien@gmail.com", "password"=>bcrypt('123456'), 'hospitals_id'=>15, 'positions_id'=>7, 'departments_id'=>1,'account_types_id'=>2, 'status'=>1]);
        $user1->roles()->attach($urgent);
        $user2 = User::create(['id'=>2, "name"=>"Tạ Thị Vân Anh",'image'=>'public\uploads\avatar.jpg','parent_id'=>0, "email"=>"tavananh@gmail.com", "password"=>bcrypt('123456'), 'hospitals_id'=>15, 'positions_id'=>3, 'departments_id'=>1,'account_types_id'=>1, 'status'=>1]);
        $user2->roles()->attach($director);
        $user3 = User::create(['id'=>1, "name"=>"Phòng tổng hợp",'image'=>'public\uploads\avatar.jpg', 'parent_id'=>1,"email"=>"vuducdam@gmail.com", "password"=>bcrypt('123456'), 'hospitals_id'=>15, 'positions_id'=>5, 'departments_id'=>1,'account_types_id'=>3, 'status'=>1]);
        $user3->roles()->attach($general);
    }
}
