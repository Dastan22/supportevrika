<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index(){
        $categories = Category::all();
        return response()->json(['categories'=>$categories], 200);
    }


    public function show($id){
        $categories = Category::find($id);
         if ($categories){
        return response()->json(['categories'=>$categories], 200);
         }
    else
        {
        return response()->json(['message'=>'Категория не найдена!'], 404);
}
    }

    public function  store(Request $request)
    {
        $this->middleware('admin');

        $attributes = $request->validate([
            'name' => 'required|unique:categories|max:100',
        ]);

        if(Category::create($attributes))
        {
            return response('Success', 201);
        }



}

 public function update(Request $request, Category $category){

     $this->middleware('admin');

     $attributes = $request->validate([
         'name' => 'required|unique:categories|max:100'
     ]);

     $category->update($attributes);
     return response('Success', 201);

 }

 public function destroy(Category $category){
     $this->middleware('admin');

     if(Category::destroy($category->id)) {
         return response('Success', 201);
     }
   }
}
