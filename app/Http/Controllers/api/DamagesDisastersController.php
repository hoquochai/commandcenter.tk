<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Mail;
use App\models\ReportType;
use App\models\Department;
use App\models\DamagesDisaster;
use App\models\AccountType;
use App\models\SeriousProblemType;
class DamagesDisastersController extends Controller
{
    public $successStatus = 200;
    public function index(){
        $user = Auth::user();
        $damages_disaster = DamagesDisaster::where('hospitals_id',$user->hospitals_id)->get();
        return response()->json(['data'=> $damages_disaster], $this->successStatus);
    }

    public function show($id){
        // return "OK";
        $damages_disaster = DamagesDisaster::find($id);

        if($damages_disaster['frequence'] == 1){
            $damages_disaster['frequence'] = "Hàng ngày";
        }else if($damages_disaster['frequence'] == 2){
            $damages_disaster['frequence'] = "Hàng tuần";
        }else{
            $damages_disaster['frequence'] = "Hàng tháng";
        }

        // Loại báo cáo
        $damages_disaster['report_types_id']= $damages_disaster->ReportType->name;
        // dd($damages_disaster); exit();
        return response()->json(['damages_disaster'=> $damages_disaster], $this->successStatus);
    }
    public function create(){
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
        $emailTo =User::where('parent_id', 0)->where('hospitals_id', $user['hospitals_id'])->first();
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
        $email_BTY = User::where('parent_id', 0)->where('hospitals_id', $user['hospitals_id'])->first();
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
            $damages_disaster= DamagesDisaster::create($request->all());
            if($damages_disaster){
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
            $file = url('public/uploads/no-image.png');
            $request->merge(['file' => $file]);
            $damages_disaster= DamagesDisaster::create($request->all());
            if($damages_disaster){
                $data = array('name'=>'Xin chào!', 'body' => 'Bạn vừa nhận được 01 email mới');
                Mail::send('emails.mail', $data, function($message) use($array) {
                    $message->to($array['mailTo'])
                    ->subject('BÁO CÁO KHẨN CẤP');
                    $message->from($array['mailFrom'], $array['title']);
                });
                return response(['success'=>'Created successfull','request'=> $request->all()],$this->successStatus);
            }
        }
    }
}
