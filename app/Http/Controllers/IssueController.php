<?php

namespace App\Http\Controllers;


use App\Http\Controllers\Controller;
use App\Http\Resources\IssueResource;
use App\Models\Issue;
use App\Models\User;
use Hamcrest\Core\Is;
use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Symfony\Component\Finder\Exception\AccessDeniedException;


class IssueController extends Controller
{

//    @Get
//    __BASE_URL__/api/issues?status=1

    public function index(Request $request)
    {

//        $status = $request->get('status') ?? Issue::STATUS_TYPE_NEW;
//        return response()->json(request(['date_after', 'date_before', 'search', 'status', 'category_id']));

        $issues = Issue::query()
            ->latest()
//            ->with('category')
            ->filter([
                'date_after' => $request->date_after,
                'date_before' => $request->date_before,
                'search' => $request->search,
                'status' => $request->status,
                'category_id' => $request->category_id,
            ])
            ->get();

//        return response()->json($issues);

        return IssueResource::collection($issues);
    }


    public function show($id)
    {

        $issues = Issue::find($id);
        if ($issues) {
            return response()->json(['issues' => $issues], 200);
        } else {
            return response()->json(['message' => 'Category not found!'], 404);
        }
    }

//        return new IssueResource($issue->load('images'));


    public function store(Request $request)
    {
        $attributes = $this->validate($request, [
            'initiator_name' => 'required|max:255',
            'text' => 'required|max:255',
            'initiator_contact' => 'required|max:255',
            'initiator_anydesk' => 'required|max:255',
            'images' => 'array'
        ]);

        $attributes['status'] = 1;
        $issue = Issue::query()->create($attributes);

        foreach ($request->images as $image) {
            $imageUrl = $this->saveImage($image);

            $issue->images()->create([
                'path' => $imageUrl,
            ]);
        }

        return response()->json(['data' => $issue->load('images')]);
    }

    public function saveImage($base64)
    {
        $image_64 = $base64; //base64 encoded data
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];   // .jpg .png .pdf
        $replace = substr($image_64, 0, strpos($image_64, ',') + 1);
        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);
        $imageName = Str::random(30) . '.' . $extension;

        $imageUrl = 'public/images/' . $imageName;
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
        return response()->json(['message' => 'Problem updated successfully'], 201);
    }


    public function destroy(Issue $issue)
    {
        $issue->delete();
        return response()->json(['message' => 'Problem removed successfully'], 201);
    }

    public function myIssues(User $user)
    {
        $issues = Issue::query()
            ->where('user_id', $user->id)
            ->get();
        return IssueResource::collection($issues);
    }

    public function takeJob(User $user, Issue $issue)
    {
        if ($issue->status == 1) {
            $issue->status = 2;
            $issue->user()->associate(auth()->user());
            $issue->save();
        } else {
            throw new \Exception("This work is in progress, choose another");
        }


        if ($issue->status == Issue::STATUS_TYPE_DURING) {
            $issue->update([
                'taken_at' => now('Asia/Almaty'),
            ]);
        }

//        return response('You have attached the problem to this dispatcher!', 200);
        return new IssueResource($issue);
    }

    public function return(User $user, Issue $issue)
    {
        if(!auth()->user()->is_admin && $issue->user_id != auth()->user()->id)
        {
            throw new AccessDeniedException();
        }

        $issue->user_id = null;
        $issue->taken_at = null;
        $issue->status = 1;
        $issue->save();

        return response('You returned this problem successfully!', 200);



    }


    public function complete(User $user, Issue $issue)
    {
        if(!auth()->user()->is_admin && $issue->user_id != auth()->user()->id)
        {
            throw new AccessDeniedException();
        }

        if (!$issue->user_id && !auth()->user()->is_admin ){
            throw new \Exception("Диспетчеру ISSUE  не присвоен ");
        }


//        $issue->user_id = null;
        $issue->taken_at = null;
        $issue->status = 0;
        $issue->save();


            return response('You completed this problem successfully!', 200);



    }
}
