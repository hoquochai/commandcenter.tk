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
            $dept_qualities = DeptQuality::orderBy('id', 'DESC')->get();
            return response()->json(['data'=> $dept_qualities], $this->successStatus);
    }
    public function show($id){

        	$dept_quality = DeptQuality::find($id);
        
        
            
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
           	// dd($dept_quality); exit();
         if($dept_quality){
         	return response()->json(['data'=> $dept_quality], $this->successStatus);
         }else{
         	return response()->json(['data'=> ''], $this->successStatus);
         }
            
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
        if ($request->hasFile('attachments')) {
            $filename = $request->file('attachments')->getClientOriginalName();
            $path = $request->file('attachments')->move("public/uploads",$filename);
            $file = url('public/uploads'.'/'.$filename);
            $request->merge(['file' => $file]);
            // dd($request->all()); exit();
            $dept_quality= DeptQuality::create($request->except('attachments'));
            if($dept_quality){
                $data = array('name'=>'Xin chào!', 'body' => 'Bạn vừa nhận được 01 email mới');
                // dd($mailToArray);exit();
                Mail::send('emails.mail', $data, function($message) use($array) {
                    $message->to($array['mailTo'])
                    ->subject('BÁO CÁO CHẤT LƯỢNG KHOA PHÒNG');
                    $message->from($array['mailFrom'],$array['title']);
                });
                return response(['success'=>'Created successfull','request'=> $request->all()], $this->successStatus);
            }

        }else{
            $dept_quality= DeptQuality::create($request->except('file','attachments'));
            if($dept_quality){
                $data = array('name'=>'Xin chào!', 'body' => 'Bạn vừa nhận được 01 email mới');
                Mail::send('emails.mail', $data, function($message) use($mailToArray) {
                    $message->to($array['mailTo'])
                    ->subject('BÁO CÁO CHẤT LƯỢNG KHOA PHÒNG');
                    $message->from($array['mailFrom'], $array['title']);
                });
                return response(['success'=>'Created successfull','request'=> $request->all()],$this->successStatus);
            }
        }

    }
}
