<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use App\models\Patient;
use App\models\Department;
use App\models\ChangePatientType;
use App\models\Hospital;

class ChangePatientTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataInsert = [];

        for ($index = 0; $index <= 100; $index++) {
            $dataInsert[] = [
                'patient_id' => app(Patient::class)->pluck('id')->random(),
                'department_id' => app(Department::class)->pluck('id')->random(),
                'patient_from_hospital_id' => app(Hospital::class)->pluck('id')->random(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        ChangePatientType::insert($dataInsert);
    }
}
