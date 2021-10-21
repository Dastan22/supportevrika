<?php

namespace App\Http\Controllers;

use App\Models\Issue;
use Illuminate\Http\Request;

class IssueController extends Controller
{

    public function index(){
        $issues = Issue::all();
        return response()->json(['issues'=>$issues], 200);
    }

    public function show($id){
        $issues = Issue::find($id);
        if ($issues){
            return response()->json(['products'=>$issues], 200);
        }
        else{
            return response()->json(['message'=>'No Product Found', 404]);
        }

    }

    public function  store(Request $request)
    {

        $request->validate([
            'initiator_name'=>'required|max:191',
            'text'=>'required|max:191',
            'image_src'=>'required|max:191',
            'initiator_contact'=>'required|max:191',
            'initiator_anydesk'=>'required|max:191',
            'dispatcher_id'=>'required|exists:users,id',
            'taken_at'=>'required|max:191',
            'category_id'=>'required|max:191',
        ], [
            "*.exists" => "Данных нет"
        ]);


        $issue = new Issue();
        $issue->initiator_name = $request->initiator_name;
        $issue->text = $request->text;
        $issue->image_src = $request->image_src;
        $issue->initiator_contact = $request->initiator_contact;
        $issue->initiator_anydesk = $request->initiator_anydesk;
        $issue->dispatcher_id = $request->dispatcher_id;
        $issue->taken_at = $request->taken_at;
        $issue->category_id = $request->category_id;
        $issue->save();
        return response()->json(['message'=> 'Issue Added Successfully'], 200);
    }

    public function update(Request $request, $id)
    {

        $request->validate([
            'initiator_name' => 'required|max:191',
            'text' => 'required|max:191',
            'image_src' => 'required|max:191',
            'initiator_contact' => 'required|max:191',
            'initiator_anydesk' => 'required|max:191',
            'dispatcher_id' => 'required|exists:users,id',
            'taken_at' => 'required|max:191',
            'category_id' => 'required|max:191',
        ], [
            "*.exists" => "Данных нет"
        ]);

            $issue = Issue::find($id);
        if ($issue)
        {
        $issue->initiator_name = $request->initiator_name;
        $issue->text = $request->text;
        $issue->image_src = $request->image_src;
        $issue->initiator_contact = $request->initiator_contact;
        $issue->initiator_anydesk = $request->initiator_anydesk;
        $issue->dispatcher_id = $request->dispatcher_id;
        $issue->taken_at = $request->taken_at;
        $issue->category_id = $request->category_id;
        $issue->update();
        return response()->json(['message' => 'Issue Updated Successfully'], 200);
        }
        else
        {
            return response()->json(['message'=>'No Issue Found'], 404);
        }
    }


    public function destroy($id){

        $issue = Issue::find($id);

        if($issue){
            $issue->delete();
            return response()->json(['message' => 'Issue Deleted Successfully'], 200);
        }else{
            return response()->json(['message'=> 'No Issue Found'], 404);
      }


    }



}
