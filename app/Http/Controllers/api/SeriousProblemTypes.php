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
    public function edit($id){
        $data = SeriousProblemType::find($id);
        return response()->json(['data'=> $data], $this->successStatus);
    }
    public function update(Request $request, $id){
        $data = SeriousProblemType::find($id);
        if($data){
            $book->update($request->all());
            return response()->json(['success'=> "Updated Successfull"], $this->successStatus);
        }else{
            return response()->json(['error'=> "Cannot find the serious problem type with id=$id"], 401);
        }
    }
}