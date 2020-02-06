<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use Auth;
use Mail;
use App\models\Complainant;
use App\models\ReportType;
use App\models\Department;
use App\models\Complain;
use App\models\AccountType;
use App\models\SeriousProblemType;

class ComplainController extends Controller
{
    public $successStatus = 200;

    public function index(Request $request)
    {
        $user = Auth::user();
        $searchData = $request->only(['key_word', 'from_date', 'to_date']);
        $query = Complain::with('complainant')->where('hospitals_id', $user->hospitals_id);

        if ($request->has('pageSize')) {
            $limit = $request->get('pageSize');
        } else {
            $limit = config('settings.limit_pagination');
        }

        $complains = $this->handleSearch($searchData, $query, 'date_complain')->orderBy('id', 'DESC')->paginate($limit);

        return response()->json(['data' => $complains], $this->successStatus);
    }

    public function show($id)
    {
        // return "OK";
        $complains = Complain::find($id);
        $complainant = Complainant::find($complains['complainants_id']);
        if($complains['frequence'] == 1){
            $complains['frequence'] = "Hàng ngày";
        } else if ($complains['frequence'] == 2) {
            $complains['frequence'] = "Hàng tuần";
        } else {
            $complains['frequence'] = "Hàng tháng";
        }
        if($complainant){
           $complains['complainants_id'] = $complainant;
        }
        // Loại báo cáo
        $complains['report_types_id'] = $complains->ReportType->name;
        // dd($complains); exit();
        return response()->json(['complains' => $complains], $this->successStatus);
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
        $complainant = new Complainant;
        $complainant->name = $request->name;
        $complainant->case_number = $request->case_number;
        $complainant->birthday = $request->birthday;
        $complainant->gender = $request->gender;
        $complainant->departments_id = $request->patient_department_id;
        $complainant->address = $request->address;
        $complainant->date_of_issue = $request->date_of_issue;
        $complainant->place_of_issue = $request->place_of_issue;
        $complainant->save();
        $complainants_id = $complainant->id;
        $request->merge(['complainants_id' => $complainants_id]);
        if ($request->hasFile('attachments')) {
            $filename = $request->file('attachments')->getClientOriginalName();
            $path = $request->file('attachments')->move("public/uploads", $filename);
            $file = url('public/uploads' . '/' . $filename);
            $request->merge(['file' => $file]);
            $complains = Complain::create($request->except('name', 'passport', 'birthday', 'gender', 'address', 'phone', 'date_of_issue', 'place_of_issue'));
            if ($complains) {
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
            $complains = Complain::create($request->except('name', 'passport', 'birthday', 'gender', 'address', 'phone', 'date_of_issue', 'place_of_issue'));
            if ($complains) {
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
