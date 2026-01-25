<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

       

        $query=Category::query();
        $keyword=$request->get("q");
        $query->where(function ($q) use ($keyword) {
            $q->where('title', 'like', "%{$keyword}%")
            ->orWhere('slug', 'like', "%{$keyword}%");

        });


        $sortBy=$request->get("sort_by") ?? "id";
        $sortDirection=$request->get("sort_direction") ?? "desc";

        $query->orderBy($sortBy,$sortDirection);
        
    
        $categories=$query->paginate(10);

        
        return CategoryResource::collection($categories);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        $validated = $request->validated();

        $category = Category::create([
            'title'   => $validated['title'],
            'slug'    => $validated['slug'] ?? null,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Category created successfully',
            'data'    => new CategoryResource($category),
        ], 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        return response()->json([
            'data'=>new CategoryResource($category)
        ],200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validated = $request->validated();

    
        $category->update($validated);

        return response()->json([
            'data'=>new CategoryResource($category),
            'message' => 'Category updated successfully.',
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        
        // Gate::authorize('delete',$category);

        $category->delete();

        return response()->json([
            'data'=>new CategoryResource($category),
            'message' => 'Category deleted successfully.',
        ]);

    }
}
