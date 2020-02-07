<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use App\models\Patient;
use App\models\Department;
use App\models\PatientHistory;

class PatientHistoryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataInsert = [];
        $dateCompare = Carbon::now();
        $dateArr = [$dateCompare->format('yy-m-d')];

        for ($indexDate = 0; $indexDate <= 7; $indexDate ++) {
            $dateArr[] = $dateCompare->subDay()->format('yy-m-d');
        }

        for ($index = 0; $index <= 100; $index++) {
            $dataInsert[] = [
                'patient_id' => app(Patient::class)->pluck('id')->random(),
                'department_id' => app(Department::class)->pluck('id')->random(),
                'is_inpatient' => Arr::random([true, false]),
                'time_go_in' => Arr::random($dateArr),
                'patient_state' => Arr::random([
                    PatientHistory::TT_TRONG_VIEN,
                    PatientHistory::TT_CHO_NHAP_VIEN,
                    PatientHistory::TT_DA_DUOC_KET_LUAN_KHAM,
                    PatientHistory::TT_DA_LAP_BENH_AN,
                    PatientHistory::TT_DA_THANH_TOAN_VIEN_PHI,
                    PatientHistory::TT_DA_RA_VIEN_VA_CHUA_THANH_TOAN,
                    PatientHistory::TT_RA_VIEN_TAM_THOI,
                    PatientHistory::TT_TINH_TAO,
                ]),
                'time_go_out' => Arr::random([null, Carbon::now()->format('yy-m-d')]),
                'transfer_department_date' => Arr::random([null, Carbon::now()->format('yy-m-d')]),
                'p_department_id' => app(Department::class)->pluck('id')->random(),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }

        PatientHistory::insert($dataInsert);
    }
}
