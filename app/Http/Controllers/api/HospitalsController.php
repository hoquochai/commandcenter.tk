<?php

namespace App\Http\Controllers\api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\models\Province;
use App\models\District;
use App\models\Ward;
use App\models\Dept;
use Auth;
use App\models\Hospital;
class HospitalsController extends Controller
{
    public $successStatus = 200;
    public function index(){
        $hospital = Hospital::all();
        return response()->json(['data'=> $hospital], $this->successStatus);
    }
    public function show($id){
        $hospital = Hospital::find($id);
        if($hospital){
            return response()->json(['data'=> $hospital], $this->successStatus);
        }else{
            return response()->json(['data'=> 'Data null'], 401);
        }
    }
    public function create(){
            $data['provinces'] = Province::all();
            $data['districts'] = District::all();
            $data['wards'] = Ward::all();
            $data['depts'] = Dept::all();
            return response()->json(['data'=> $data], $this->successStatus);
    }
    public function store(Request $request){
        $hospitals = Hospital::all();
        if($hospitals->count()!=0)
        {
            foreach($hospitals as $k=> $value){
                if($value['code'] === $request->code){
                    return response(['error' => 'Hospital is existed'], 401);
                }
                else{
                   if ($request->hasFile('file')) {
                        $filename = $request->file->getClientOriginalName();
                        $path = $request->file->move("public/uploads",$filename);
                        $logoUrl = url('public/uploads'.'/'.$filename);
                        $request->merge(['logo' => $logoUrl]);
                        $hospital= Hospital::create($request->all());
                        return response(['success'=>'Created successfull','request'=> $request->all()], $this->successStatus);
                    }else{
                        $filename = 'public/uploads/no-image.png';
                        $logoUrl = url('public/uploads'.'/'.$filename);
                        $request->merge(['logo' =>  $filename]);
                        $hospital= Hospital::create($request->all());
                        return response(['success'=>'Created successfull','request'=> $request->all()],$this->successStatus);
                    }
                }
            }
        }else{

            if ($request->hasFile('file')) {
                        $filename = $request->file->getClientOriginalName();
                        $path = $request->file->move("public/uploads",$filename);
                        $logUrl = url('public/uploads'.'/'.$filename);
                        $request->merge(['logo' => $logUrl]);
                        $hospital= Hospital::create($request->all());
                        return response(['success'=>'Created successfull','request'=> $request->all()],$this->successStatus);
                    }else{
                        $request->merge(['logo' => 'public/uploads/no-image.png']);
                        $hospital= Hospital::create($request->all());
                        return response(['success'=>'Created successfull','request'=> $request->all()],$this->successStatus);
                    }
        }
    }
    public function edit($id){
        $hospital = Hospital::find($id);
        if(!$hospital){
            return response()->json(['data'=> 'Data null'], 401);
        }else{
            return response()->json(['data'=> $hospital], $this->successStatus);
        }
    }
    public function update(Request $request, $id){
        $hospital = Hospital::find($id);
        if ($request->hasFile('file')) {
            $filename = $request->file->getClientOriginalName();
            $logo = $request->file->getClientOriginalName();
            $path = $request->file->move("public/uploads",$logo);
            $logoUrl = url('public/uploads'.'/'.$logo);
            $request->merge(['logo' => $logoUrl]);
            if($request->code == $hospital['code']){
                $hospital->name = $request->name;
                $hospital->address = $request->address;
                $hospital->logo = $request->logo;
                $hospital->provinces_id = $request->provinces_id;
                $hospital->districts_id = $request->districts_id;
                $hospital->wards_id = $request->wards_id;
                $hospital->depts_id = $request->depts_id;
                $hospital->save();
            } else{
                $hospital->name = $request->name;
                $hospital->code = $request->code;
                $hospital->address = $request->address;
                $hospital->logo = $request->logo;
                $hospital->provinces_id = $request->provinces_id;
                $hospital->districts_id = $request->districts_id;
                $hospital->wards_id = $request->wards_id;
                $hospital->depts_id = $request->depts_id;
                $hospital->save();
            } 
            return response()->json(['success'=> "Updated Successfull"], $this->successStatus);
       }else{
            if($request->code == $hospital['code']){
                $hospital->name = $request->name;
                $hospital->address = $request->address;
                $hospital->provinces_id = $request->provinces_id;
                $hospital->districts_id = $request->districts_id;
                $hospital->wards_id = $request->wards_id;
                $hospital->depts_id = $request->depts_id;
                $hospital->save();
            } else{
                $hospital->name = $request->name;
                $hospital->code = $request->code;
                $hospital->address = $request->address;
                $hospital->provinces_id = $request->provinces_id;
                $hospital->districts_id = $request->districts_id;
                $hospital->wards_id = $request->wards_id;
                $hospital->depts_id = $request->depts_id;
                $hospital->save();
            } 
            return response()->json(['success'=> "Updated Successfull"], $this->successStatus);
       }
    }
    public function destroy($id){
        $hospital = Hospital::destroy($id);
        if($hospital){
            return response()->json(['success'=> "Deleted Successfull"], $this->successStatus);
        }else{
            return response()->json(['error'=> "Cannot find hospital"], $this->successStatus);
        }
    }
}
