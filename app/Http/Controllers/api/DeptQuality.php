<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\SeriousProblemType;
use App\models\DeptQuality;
use App\models\Patient;
use App\models\ReportType;
use App\models\Department;
use App\models\AccountType;
use App\User;
use Auth;
use Mail;
class DeptQualityController extends Controller
{
    public $successStatus = 200;
    public function index(){
            $dept_qualitys = DeptQuality::orderBy('id', 'DESC')->get();
            return response()->json(['data'=> $dept_qualitys], $this->successStatus);
    }
    public function show($id){
        $dept_quality = DeptQuality::find($id);
            // serious_problem_types
            $arr = explode(',',$dept_quality['serious_problem_types_id']);
            // dd($arr); exit();
            foreach($arr as $key1 => $value1){
                $data[] = SeriousProblemType::where('id', $value1)->first()->toArray();
            }
            $dept_quality['serious_problem_types'] = buildTree($data, 0);
            
            // Tần suất báo cáo
            if($dept_quality['frequence'] == 1){
                $dept_quality['frequence'] = "Hàng ngày";
            }else if($dept_quality['frequence'] == 2){
                $dept_quality['frequence'] = "Hàng tuần";
            }else{
                $dept_quality['frequence'] = "Hàng tháng";
            }
            // Loại báo cáo
            $dept_quality['report_types_id']= $dept_quality->ReportType->name;
            // Thông báo cho bác sĩ
            if($dept_quality['notify_doctor']==1){
                $dept_quality['notify_doctor'] = "Có";
            }else if($dept_quality['notify_doctor'] == 2){
                $dept_quality['notify_doctor'] = "Không";
            }else{
                $dept_quality['notify_doctor'] = "Không ghi nhận";
            }
            // Thông báo cho người nhà bệnh nhân
            if($dept_quality['notify_family']==1){
                $dept_quality['notify_family'] = "Có";
            }else if($dept_quality['notify_family'] == 2){
                $dept_quality['notify_family'] = "Không";
            }else{
                $dept_quality['notify_family'] = "Không ghi nhận";
            }
            // Thông báo cho người bệnh
            if($dept_quality['notify_patient']==1){
                $dept_quality['notify_patient'] = "Có";
            }else if($dept_quality['notify_patient'] == 2){
                $dept_quality['notify_patient'] = "Không";
            }else{
                $dept_quality['notify_patient'] = "Không ghi nhận";
            }
            // Mức độ nghiêm trọng
            if($dept_quality['trouble_level']==1){
                $dept_quality['trouble_level'] = "Nặng";
            }else if($dept_quality['trouble_level'] == 2){
                $dept_quality['trouble_level'] = "Nhẹ";
            }else{
                $dept_quality['trouble_level'] = "Trung bình";
            }
            // Lưu hồ sơ y tế
            if($dept_quality['recorded_medical ']==1){
                $dept_quality['recorded_medical '] = "Có";
            }else if($dept_quality['recorded_medical '] == 2){
                $dept_quality['recorded_medical '] = "Không";
            }else{
                $dept_quality['recorded_medical '] = "Không ghi nhận";
            }
            // Đối tượng xảy ra sự cố
            if($dept_quality['problem_object ']==1){
                $dept_quality['problem_object '] = "Người bệnh";
            }else if($dept_quality['problem_object '] == 2){
                $dept_quality['problem_object '] = "Người nhà";
            }else if($dept_quality['problem_object '] == 3){
                $dept_quality['problem_object '] = "Nhân viên y tế";
            }else{
                $dept_quality['problem_object '] = "Trang thiết bị y tế";
            }
           
            return response()->json(['data'=> $dept_quality], $this->successStatus);
    }
    public function create(){
        // dd($user); exit();
        $user = Auth::user();

        $data['departments'] = Department::where('hospitals_id', $user->hospitals_id)->first();
        $data['ReportTypes'] = ReportType::all();
        $data['SeriousProblemTypes'] = SeriousProblemType::all();
        // MailTo cũ
        // $account_types = AccountType::where('code','BYT')->first();
        // $email_BTY = User::where('account_types_id', $account_types['id'])->first();
        //  $receiver[] = $email_BTY;
        // if(isset($user['parent_id'])){
        //     $user_dept = User::where('account_types_id', $user['parent_id'])->first();
        //     $receiver[] = $user_dept;
        // }
        // $data['receiver'] = $receiver;
        // MailTo mới
        $emailTo = User::where('parent_id',  $user['parent_id'])->first();
        // dd($user); exit();
        $data['receiver'] = $emailTo;
        return response()->json(['success'=> $data], $this->successStatus);
    }
    public function store(Request $request){
        // Get email
        $user = Auth::user();
        // code cũ
        // $account_types = AccountType::where('code','BYT')->first();
        // $email_BTY = User::where('account_types_id', $account_types['id'])->first();
        // $parent_id = $user['parent_id'];
        // $receiver = array();
        // if(isset($user['parent_id'])){
        //     $user_dept = User::where('account_types_id', $user['parent_id'])->first();
        //     $mailTo = $user_dept['email'].','.$email_BTY['email'];
        // }
        // Code mới;
        $email_BTY = User::where('parent_id', 1)->first();
        // ------
        $mailTo = $email_BTY['email'];
        $mailFrom = $user['email'];
        $array['mailTo'] = $mailTo;
        $array['mailFrom'] = $mailFrom;
        $array['title'] = $request->title;
        // Patient Table
        $patients = new Patient;
        $patients->name = $request->name;
        $patients->case_number = $request->case_number;
        $patients->birthday = $request->birthday;
        $patients->gender= $request->gender;
        $patients->departments_id= $request->patient_department_id;
        $patients->save();
        $patients_id = $patients->id;
        $request->merge(['patients_id' =>  $patients_id]);
        if ($request->hasFile('attachments')) {
            $filename = $request->file('attachments')->getClientOriginalName();
            $path = $request->file('attachments')->move("public/uploads",$filename);
            $file = url('public/uploads'.'/'.$filename);
            $request->merge(['file' => $file]);
            $dept_quality= UrgentReport::create($request->except('name', 'case_number','birthday','gender','patient_department_id'));
            if($dept_quality){
                $data = array('name'=>'Xin chào!', 'body' => 'Bạn vừa nhận được 01 email mới');
                // dd($mailToArray);exit();
                Mail::send('emails.mail', $data, function($message) use($array) {
                    $message->to($array['mailTo'])
                    ->subject('BÁO CÁO KHẨN CẤP');
                    $message->from($array['mailFrom'],$array['title']);
                });
                return response(['success'=>'Created successfull','request'=> $request->all()], $this->successStatus);
            }

        }else{
            $dept_quality= UrgentReport::create($request->except('file','name', 'case_number','birthday','gender','patient_department_id','patient_hospital_id'));
            if($dept_quality){
                $data = array('name'=>'Xin chào!', 'body' => 'Bạn vừa nhận được 01 email mới');
                Mail::send('emails.mail', $data, function($message) use($mailToArray) {
                    $message->to($array['mailTo'])
                    ->subject('BÁO CÁO KHẨN CẤP');
                    $message->from($array['mailFrom'], $array['title']);
                });
                return response(['success'=>'Created successfull','request'=> $request->all()],$this->successStatus);
            }
        }

    }
}
