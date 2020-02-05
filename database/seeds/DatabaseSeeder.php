<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            DeptsTableSeeder::class,
            HospitalsTableSeeder::class,
            AreasTableSeeder::class,
            PositionsTableSeeder::class,
            DepartmentsTableSeeder::class,
            ReportTypesTableSeeder::class,
            RolesTableSeeder::class,
            SeriousProblemTypesTableSeeder::class,
            AccountTypeTableSeeder::class,
            UsersTableSeeder::class,
        ]);
    }
}
