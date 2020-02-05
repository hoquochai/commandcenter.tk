<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Auth;
use App\models\Position;
class PositionController extends Controller
{
    public $successStatus = 200;
    public function index(){
        $position = Position::all();
        return response()->json(['data'=> $position], $this->successStatus);
    }
    public function show($id){
        $position = Position::find($id);
        if($position){
            return response()->json(['data'=> $position], $this->successStatus);
        }else{
            return response()->json(['data'=> 'Data null'], 401);
        }
    }
    public function store(Request $request){
        $position= Position::create($request->all());
        return response()->json(['data'=> $position], $this->successStatus);
    }
    public function edit($id){
        $position = Position::find($id);
        if(!$position){
            return response()->json(['data'=> 'Data null'], 401);
        }else{
            return response()->json(['data'=> $position], $this->successStatus);
        }
    }
    public function update(Request $request, $id){
        $position = Position::find($id);
        $position->name = $request->name;
        $position->save();
        return response()->json(['success'=> "Updated Successfull"], $this->successStatus);
    }
    public function destroy($id){
        $position = Position::destroy($id);
        if($position){
            return response()->json(['success'=> "Deleted Successfull"], $this->successStatus);
        }else{
            return response()->json(['error'=> "Cannot find Positions"], $this->successStatus);
        }

    }
}
