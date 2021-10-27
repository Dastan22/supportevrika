<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
 public function register(Request  $request){
     $data = $request->validate([
         'name'=>'required|string|max:191',
         'email'=>'required|email|max:191|unique:users,email',
         'password'=>'required|string',
//
     ]);

     $user = User::create([
         'name' =>$data['name'],
         'email'=> $data['email'],
         'password'=>Hash::make($data['password']),
     ]);

     $token = $user->createToken('fundaProjectToken')->plainTextToken;

     $response = [
         'user'=>$user,
         'token'=>$token,
     ];
     return response($response, 201);
 }

 public function logout(){
     auth()->user()->tokens()->delete();
     return response(['message'=>'Выход успешно выполнен']);
 }

    public function login(Request $request){
        $data = $request->validate([
        'email' => 'required|email|max:191',
        'password' => 'required|string',
     ]);

     $user = User::query()->where('email', $data['email'])->first();

     if(!$user || !Hash::check($data['password'], $user->password)){

         return response(['message'=> 'Неверные учетные данные'], 401);
         }
     else{
         $token = $user->createToken('fundaProjectTokenLogin')->plainTextToken;
         $user->load('roles');
         $response = [
             'user'=>  $user,
             'token'=> $token,
         ];
         return response($response, 200);
     }
    }
}
