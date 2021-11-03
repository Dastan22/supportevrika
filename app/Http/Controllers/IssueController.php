<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Resources\IssueResource;
use App\Models\Issue;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;


class IssueController extends Controller
{

//    @Get
//    __BASE_URL__/api/issues?status=1

    public function index(Request $request){

        $status = $request->get('status') ?? Issue::STATUS_TYPE_NEW;

        $issues = Issue::query()
            ->latest()
            ->where('status', $status)
            ->filter(request(['date_after', 'date_before', 'search', 'status', 'category_id']))
            ->get();

        return IssueResource::collection($issues);
    }


    public function show(Issue $issue){

        return new IssueResource($issue->load('images'));

    }

    public function  store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'initiator_name'=>'required|max:21',
            'text'=>'required|max:191',
            'initiator_contact'=>'required|max:11',
            'initiator_anydesk'=>'required|max:11',
//            'dispatcher_id'=>'required|exists:users,id',
//            'category_id'=>'required|max:21',
//            'taken_at'=>'required|max:21',
        ], [
            "*.exists" => "Данных нет"
        ]);


        $issue = Issue::query()->create([
            "initiator_name" => $request->initiator_name,
            "text" => $request->text,
            "initiator_contact" => $request->initiator_contact,
            "status" => 1,
            "initiator_anydesk" => $request->initiator_anydesk,
//            "dispatcher_id" => $request->dispatcher_id,
//            "category_id" => $request->category_id,
//            "taken_at" => $request->taken_at,
        ]);


        if ( is_array($request->images) ) {
            foreach ($request->images as $image) {
                $imageUrl = $this->saveImage($image);

                $issue->images()->create([
                    'path' => $imageUrl,
                ]);
            }
        }

        return response()->json(['data' => $issue]);
    }

    public function saveImage ($base64) {
        $image_64 = $base64; //base64 encoded data
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($image_64, 0, strpos($image_64, ',')+1);
        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(30).'.'.$extension;

        $imageUrl = 'public/images'.$imageName;
        Storage::disk()->put($imageUrl, base64_decode($image));

        return $imageUrl;
    }


    public function update(Request $request, Issue $issue)
    {

        $request->validate([
            'initiator_name' => 'required|max:191',
            'text' => 'required|max:191',
//            'image_src' => 'required|max:191',
            'initiator_contact' => 'required|max:191',
            'initiator_anydesk' => 'required|max:191',

        ], [
            "*.exists" => "Данных нет"
        ]);


            $issue->initiator_name = $request->initiator_name;
            $issue->text = $request->text;
            $issue->initiator_contact = $request->initiator_contact;
            $issue->initiator_anydesk = $request->initiator_anydesk;

            $issue->update();
            return response()->json(['message' => 'Проблема успешно обновлена'], 200);
    }


    public function destroy(Issue $issue){
        $issue->delete();
        return response()->noContent();
    }



    public function beginWork(Request $request, Issue $issue)
    {
        $this->validate($request, [
            'status' => 'required|in:0,2'
        ]);

        if ( $issue->status == Issue::STATUS_TYPE_DURING ) {
            $issue->update([
                'taken_at' => now('Asia/Almaty'),
            ]);
        }

        $issue->update(['status' => $request->status]);

        return new IssueResource($issue);
    }
}
