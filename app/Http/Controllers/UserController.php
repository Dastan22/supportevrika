<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
//        return User::where('is_admin','0')->paginate(15);
        return UserResource::collection(User::where('is_admin','0')->paginate(15));
    }


    public function store(Request $request)
    {

        $attributes = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|password'
        ]);

        if($request->is_admin==1){
            $attributes['is_admin'] = 1;
            $admin = User::create($attributes);
            return new UserResource($admin);
        }
        $attributes['password'] = bcrypt($request->password);
        $dispatcher = User::create($attributes);
        return new UserResource($dispatcher);
    }

    public function show(User $dispatcher)
    {
        // return view('issue', [
        //     'issue' => $issue,
        //     'issue_status' => $issue->status
        // ]);
        return $dispatcher;
    }

    public function update(Request $request, User $dispatcher)
    {
        $attributes = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|password'
        ]);

        $attributes['password'] = bcrypt($request->password);
        $dispatcher->update($attributes);
        return response('Success', 200);
    }

    public function destroy(User $dispatcher)
    {
        if(User::destroy($dispatcher->id))
        {
            return response('Success', 200);
        }

    }
}
