<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Mail;
use App\models\AssaultedStaff;
use App\models\ReportType;
use App\models\Department;
use App\models\Assaulted;
use App\models\AccountType;
use App\models\SeriousProblemType;

class AssaultedStaffController extends Controller
{
    public $successStatus = 200;

    public function index(Request $request)
    {
        $user = Auth::user();
        $searchData = $request->only(['key_word', 'from_date', 'to_date']);
        $query = Assaulted::where('hospitals_id', $user->hospitals_id);

        if ($request->has('pageSize')) {
            $limit = $request->get('pageSize');
        } else {
            $limit = config('settings.limit_pagination');
        }

        $assaulted = $this->search($searchData, $query, 'date_assaulted')->orderBy('id', 'DESC')->paginate($limit);
        return response()->json(['data' => $assaulted], $this->successStatus);
    }

    public function show($id)
    {
        // return "OK";
        $assaulted = Assaulted::find($id);

        if ($assaulted['frequence'] == 1) {
            $assaulted['frequence'] = "Hàng ngày";
        } else if ($assaulted['frequence'] == 2) {
            $assaulted['frequence'] = "Hàng tuần";
        } else {
            $assaulted['frequence'] = "Hàng tháng";
        }

        // Loại báo cáo
        $assaulted['report_types_id'] = $assaulted->ReportType->name;
        // dd($assaulted); exit();
        return response()->json(['assaulted' => $assaulted], $this->successStatus);
    }

    public function create()
    {
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
        $emailTo = User::where('parent_id', 0)->where('hospitals_id', $user['hospitals_id'])->first();
        // dd($user); exit();
        $data['receiver'] = $emailTo;
        return response()->json(['success' => $data], $this->successStatus);
    }

    public function store(Request $request)
    {
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
        // Create Assaulted Staff

        $assaulted_staff = new AssaultedStaff;
        $assaulted_staff->name = $request->name;
        $assaulted_staff->passport = $request->passports;
        $assaulted_staff->birthday = $request->birthday;
        $assaulted_staff->gender = $request->gender;
        $assaulted_staff->phone = $request->phone;
        $assaulted_staff->address = $request->address;
        $assaulted_staff->departments_id = $request->staff_departments_id;
        $assaulted_staff->hospitals_id = $request->staff_hospitals_id;
        $assaulted_staff->date_of_issue = $request->date_of_issue;
        $assaulted_staff->place_of_issue = $request->place_of_issue;
        $assaulted_staff->save();
        $assaulted_staffs_id = $assaulted_staff->id;
        // dd($assaulted_staffs_id); exit();
        $request->merge(['assaulted_staffs_id'=>$assaulted_staffs_id]); //assaulted_staffs_id
        // dd($request->all()); exit();
        if ($request->hasFile('attachments')) {
            $filename = $request->file('attachments')->getClientOriginalName();
            $path = $request->file('attachments')->move("public/uploads",$filename);
            $file = url('public/uploads'.'/'.$filename);
            $request->merge(['file' => $file]);
            // dd($request->all()); exit();
            $assaulted= Assaulted::create($request->except('name', 'passports','birthday','gender','address','phone','staff_departments_id','staff_hospitals_id'));
            if($assaulted){
                $data = array('name'=>'Xin chào!', 'body' => 'Bạn vừa nhận được 01 email mới');
                // dd($mailToArray);exit();
                Mail::send('emails.mail', $data, function ($message) use ($array) {
                    $message->to($array['mailTo'])
                    ->subject('BÁO CÁO KHẨN CẤP');
                    $message->from($array['mailFrom'],$array['title']);
               });
               return response(['success'=>'Created successfull','request'=> $request->all()], $this->successStatus);
            }else{
                return response(['error'=>'Cannot created'], 401);
            }
        } else {
            $file = url('public/uploads/no-image.png');
            $request->merge(['file' => $file]);
            dd($request->all()); exit();
            $assaulted= Assaulted::create($request->except('name', 'passports','birthday','gender','address','phone','staff_departments_id','staff_hospitals_id'));
            if($assaulted){
                $data = array('name'=>'Xin chào!', 'body' => 'Bạn vừa nhận được 01 email mới');
                Mail::send('emails.mail', $data, function($message) use($array) {
                    $message->to($array['mailTo'])
                        ->subject('BÁO CÁO KHẨN CẤP');
                    $message->from($array['mailFrom'], $array['title']);
                });
                return response(['success' => 'Created successfull', 'request' => $request->all()], $this->successStatus);
            }
        }
    }
}
