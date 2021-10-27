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
        $request->validate([
        'name'=>'required|max:191',
    ]);

    $category = new Category;
    $category->name = $request->name;
    $category->save();
    return response()->json(['message'=>'Категория успешно добавлена!'], 200);
}

 public function update(Request $request, $id){

     $request->validate([
         'name'=>'required|max:191',
     ]);

     $category = Category::find($id);
      if ($category){
     $category->name = $request->name;
     $category->update();
     return response()->json(['message'=>'Категория обновлена успешно!'], 200);
      }
      else{
          return response()->json(['message'=>'No Category Found'], 404);
      }

 }

 public function destroy($id){
        $category = Category::find($id);
        if ($category){
            $category->delete();
            return response()->json(['message'=>'Категория успешно удалена'], 200);
        }
        else{
            return response()->json(['message'=>'Категория не найдена'],404);
        }
 }


}
