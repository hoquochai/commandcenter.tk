<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\AccountType;
class AccountTypeController extends Controller
{
    public $successStatus = 200;
    public function index(){
        $account_type = AccountType::all();
        return response()->json(['data'=> $account_type], $this->successStatus);
    }
    public function show($id){
        $account_type = AccountType::find($id);
        if($account_type){
            return response()->json(['data'=> $account_type], $this->successStatus);
        }else{
            return response()->json(['data'=> 'Data null'], 401);
        }
    }
    public function store(Request $request){
        $account_type= AccountType::create($request->all());
        return response()->json(['data'=> $account_type], $this->successStatus);
    }
    public function edit($id){
        $account_type = AccountType::find($id);
        if(!$account_type){
            return response()->json(['data'=> 'Data null'], 401);
        }else{
            return response()->json(['data'=> $account_type], $this->successStatus);
        }
    }
    public function update(Request $request, $id){
        $account_type = AccountType::find($id);
        $account_type->name = $request->name;
        $account_type->code = $request->code;
        $account_type->save();
        return response()->json(['success'=> "Updated Successfull"], $this->successStatus);
    }
    public function destroy($id){
        $account_type = AccountType::destroy($id);
        if($account_type){
            return response()->json(['success'=> "Deleted Successfull"], $this->successStatus);
        }else{
            return response()->json(['error'=> "Cannot find Account Types"], $this->successStatus);
        }
    }
}
