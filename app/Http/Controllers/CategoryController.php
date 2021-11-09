<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{

    public function index()
    {
        $categories = Category::all();
        return response()->json(['categories' => $categories], 201);
    }


    public function show($id)
    {
        $categories = Category::find($id);
        if ($categories) {
            return response()->json(['categories' => $categories], 201);
        } else {
            return response()->json(['message' => 'Category not found!'], 404);
        }
    }

    public function store(Request $request)
    {


        $attributes = $request->validate([
            'name' => 'required|unique:categories|max:100',
        ]);

        if (Category::create($attributes)) {
            return response()->json(['message'=>'Category successfully created'], 201);
        }


    }

    public function update(Request $request, Category $category)
    {


        $attributes = $request->validate([
            'name' => 'required|unique:categories|max:100'
        ]);

        $category->update($attributes);
        return response()->json(['message'=>'Category successfully updated'], 201);

    }

    public function destroy(Category $category)
    {


        if (Category::destroy($category->id)) {
            return response()->json(['message'=>'Category successfully completed'], 201);
        }
    }
}
