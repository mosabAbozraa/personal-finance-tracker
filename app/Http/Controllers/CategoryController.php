<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CategoryController extends Controller
{
    public function add_category(CategoryRequest $request){
        $user_id = Auth::id();
        $validateData = $request->validated();
        $validateData['user_id'] = $user_id;
        $category = Category::create($validateData);

        Log::info('Category created', [
            'user_id' => $user_id, 'category_id' => $category->id
        ]);

        return response()->json([
            'message'  => 'category created successfully',
            'category' => $category]
            , 201);
    }

    public function get_categories(){
        $user = Auth::user();
        $categories = $user->categories;

        return response()->json([
            'message'    => 'You have ' . $categories->count() . ' categories.',
            'categories' => $categories
        ], 200);
    }

    public function get_one_category($id){
        $category = Category::where('user_id',Auth::id())->find($id);
        if($category === null){
            return response()->json('Category not found', 404);
        }

        return response()->json([
            'category' => $category
        ], 200);
    }

    public function update_category(Request $request, $id){
        $validateData = $request->validate([
            'name'  => 'sometimes|string|max:50',
            'type'  => 'sometimes|in:income,expense'
        ]);
        $category = Category::where('user_id',Auth::id())->find($id);
        if($category === null){
            return response()->json('Category not found', 404);
        }
        $category->update($validateData);

        return response()->json([
            'message' => 'category updated successfully',
            'category'  => $category
        ], 200);
    }

    public function delete_category($id){
        $category = Category::where('user_id',Auth::id())->find($id);
        if($category === null){
            return response()->json('Category not found', 404);
        }

        Log::warning('Category deleted', [
            'user_id' => Auth::id(), 'category_id' => $category->id
        ]);

        $category->delete();

        return response()->json(null, 204);
    }
}
