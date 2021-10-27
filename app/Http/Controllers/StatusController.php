<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Models\Status;
use Illuminate\Http\Request;

class StatusController extends Controller
{
    public function index(){
        $statuses = Status::all();
        return response()->json(['statuses'=>$statuses], 200);
    }

    public function  store(Request $request)
    {
        $request->validate([
            'name'=>'required|max:191',
        ]);

        $status = new Status;
        $status->name = $request->name;
        $status->save();
        return response()->json(['message'=>'Статус добавлен успешно'], 200);
    }

    public function show($id){
        $statuses = Status::find($id);
        if ($statuses){
            return response()->json(['statuses'=>$statuses], 200);
        }
        else
        {
            return response()->json(['message'=>'Статус не найден'], 404);
        }
    }

    public function update(Request $request, $id){

        $request->validate([
            'name'=>'required|max:191',
        ]);

        $status = Status::find($id);
        if ($status){
            $status->name = $request->name;
            $status->update();
            return response()->json(['message'=>'Статус обновлен успешно'], 200);
        }
        else{
            return response()->json(['message'=>'Статус не найден'], 404);
        }

    }
public  function destroy($id){

        $status = Status::find($id);
        if ($status){
            $status->delete();
            return response()->json(['message'=>'Статус успешно удален'], 200);

        }else{
            return response()->json(['message'=>'Статус не найден', 404]);
        }

}


}
