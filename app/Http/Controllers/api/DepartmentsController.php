<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\models\Department;
class DepartmentsController extends Controller
{
    public $successStatus = 200;
    public function index(){
        $departments = Department::all();
        $data = buildTree($departments, 0);
        return response()->json(['data'=> $data], $this->successStatus);
    }
    public function create(){
        $departments = Department::all();
        $data = buildTree($departments, 0);
        return response()->json(['data'=> $data], $this->successStatus);
    }
    public function store(Request $request){
        $input = $request->all();
        Department::create($input);
        return response()->json(['success'=> "Created Successfull"], $this->successStatus);
    }
    public function edit($id){
        $data = Department::find($id);
        return response()->json(['data'=> $data], $this->successStatus);
    }
    public function update(Request $request, $id){
        $data = Department::find($id);
        if($data){
            $data->name = $request->name;
            $data->save();
            return response()->json(['success'=> "Updated Successfull"], $this->successStatus);
        }else{
            return response()->json(['error'=> "Cannot find the serious problem type with id=$id"], 401);
        }
    }
    public function destroy($id){
        $result = Department::destroy($id);
        if($result){
            return response()->json(['success'=> "Deleted Successfull"], $this->successStatus);
        }else{
            return response()->json(['error'=> "Cannot find hospital"], $this->successStatus);
        }

    }
}
