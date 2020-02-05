<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Mail;
use App\models\ReportType;
use App\models\Department;
use App\models\LaborAccident;
use App\models\AccountType;
use App\models\SeriousProblemType;

class LaborAccidentContoller extends Controller
{
    public $successStatus = 200;

    public function index(Request $request)
    {
        $user = Auth::user();
        $searchData = $request->only(['key_word', 'from_date', 'to_date']);
        $query = LaborAccident::where('hospitals_id', $user->hospitals_id);

        if ($request->has('pageSize')) {
            $limit = $request->get('pageSize');
        } else {
            $limit = config('settings.limit_pagination');
        }

        $labor_accidents = $this->search($searchData, $query, 'date_complain')->orderBy('id', 'DESC')->paginate($limit);
        return response()->json(['data' => $labor_accidents], $this->successStatus);
    }

    public function show($id)
    {
        // return "OK";
        $labor_accidents = LaborAccident::find($id);

        if ($labor_accidents['frequence'] == 1) {
            $labor_accidents['frequence'] = "Hàng ngày";
        } else if ($labor_accidents['frequence'] == 2) {
            $labor_accidents['frequence'] = "Hàng tuần";
        } else {
            $labor_accidents['frequence'] = "Hàng tháng";
        }

        // Loại báo cáo
        $labor_accidents['report_types_id'] = $labor_accidents->ReportType->name;
        // dd($labor_accidents); exit();
        return response()->json(['labor_accidents' => $labor_accidents], $this->successStatus);
    }

    public function create()
    {
        $user = Auth::user();
        $data['departments'] = Department::where('hospitals_id', $user->hospitals_id)->first();
        $data['ReportTypes'] = ReportType::all();
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
        // Patient Table
        $labor_accidents = new LaborAccident;
        $labor_accidents->name = $request->name;
        $labor_accidents->case_number = $request->case_number;
        $labor_accidents->birthday = $request->birthday;
        $labor_accidents->gender = $request->gender;
        $labor_accidents->departments_id = $request->patient_department_id;
        $labor_accidents->address = $request->address;
        $labor_accidents->date_of_issue = $request->date_of_issue;
        $labor_accidents->place_of_issue = $request->place_of_issue;
        $labor_accidents->save();
        $labor_accidents_id = $labor_accidents->id;
        $request->merge(['complainants_id' => $complainants_id]);
        if ($request->hasFile('attachments')) {
            $filename = $request->file('attachments')->getClientOriginalName();
            $path = $request->file('attachments')->move("public/uploads", $filename);
            $file = url('public/uploads' . '/' . $filename);
            $request->merge(['file' => $file]);
            $labor_accidents = LaborAccident::create($request->all());
            if ($labor_accidents) {
                $data = array('name' => 'Xin chào!', 'body' => 'Bạn vừa nhận được 01 email mới');
                // dd($mailToArray);exit();
                Mail::send('emails.mail', $data, function ($message) use ($array) {
                    $message->to($array['mailTo'])
                        ->subject('BÁO CÁO KHẨN CẤP');
                    $message->from($array['mailFrom'], $array['title']);
                });
                return response(['success' => 'Created successfull', 'request' => $request->all()], $this->successStatus);
            }

        } else {
            $file = url('public/uploads/no-image.png');
            $request->merge(['file' => $file]);
            $labor_accidents = LaborAccident::create($request->all());
            if ($labor_accidents) {
                $data = array('name' => 'Xin chào!', 'body' => 'Bạn vừa nhận được 01 email mới');
                Mail::send('emails.mail', $data, function ($message) use ($array) {
                    $message->to($array['mailTo'])
                        ->subject('BÁO CÁO KHẨN CẤP');
                    $message->from($array['mailFrom'], $array['title']);
                });
                return response(['success' => 'Created successfull', 'request' => $request->all()], $this->successStatus);
            }
        }
    }
}
