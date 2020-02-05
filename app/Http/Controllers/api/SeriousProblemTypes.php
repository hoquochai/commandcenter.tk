<?php

namespace App\Http\Controllers\api;
use App\models\SeriousProblemType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SeriousProblemTypes extends Controller
{
    public $successStatus = 200;
    public function index(){
        $problem_types = SeriousProblemType::all();
        $data = buildTree($problem_types, 0);
        return response()->json(['data'=> $data], $this->successStatus);
    }
    public function create(){
        $problem_types = SeriousProblemType::all();
        $data = buildTree($problem_types, 0);
        return response()->json(['data'=> $data], $this->successStatus);
    }
    public function store(Request $request){
        $input = $request->all();
        SeriousProblemType::create($input);
        return response()->json(['success'=> "Created Successfull"], $this->successStatus);
    }
    public function edit($id){
        $data['types'] = SeriousProblemType::find($id);
        $problem_types = SeriousProblemType::all();
        $data['problem_types'] = buildTree($problem_types, 0);
        return response()->json(['data'=> $data], $this->successStatus);
    }
    public function update(Request $request, $id){
        $data = SeriousProblemType::find($id);
        if($data){
            $data->name = $request->name;
            $data->parent_id = $request->parent_id;
            $data->content = $request->content;
            $data->save();
            return response()->json(['success'=> "Updated Successfull"], $this->successStatus);
        }else{
            return response()->json(['error'=> "Cannot find the serious problem type with id=$id"], 401);
        }
    }
    public function destroy($id){
        $result = SeriousProblemType::destroy($id);
        if($result){
            return response()->json(['success'=> "Deleted Successfull"], $this->successStatus);
        }else{
            return response()->json(['error'=> "Cannot find hospital"], $this->successStatus);
        }

    }
}
