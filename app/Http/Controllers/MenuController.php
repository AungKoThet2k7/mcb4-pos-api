<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Http\Requests\StoreMenuRequest;
use App\Http\Requests\UpdateMenuRequest;
use App\Http\Resources\MenuResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Search params
        $searchTerm = $request->get('q');
        $priceMin = $request->input("price_min");
        $priceMax = $request->input("price_max");
        $limit = $request->input('limit', 10);

        // Sort params
        $validSortColumn = ['id', 'title', 'price'];
        $sortBy = in_array($request->input('sort_by'), $validSortColumn, true) ? $request->input('sort_by') : 'id';
        $sortDirection = in_array($request->input('sort_direction'), ['asc', 'desc'], true) ? $request->input('sort_direction') : 'desc';
        $limit = is_numeric($limit) && $limit > 0 && $limit <= 100 ? (int) $limit : 10;

        // Initialize query with user scope
        $query = Menu::query()->where('user_id', Auth::id())->with('category');

        // Apply search filter if search term exists
        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('slug', 'like', "%{$searchTerm}%")
                    ->orWhereHas('category', function ($cat) use ($searchTerm) {
                        $cat->where('title', 'like', "%{$searchTerm}%")
                            ->orWhere('slug', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // Apply price range filter
        if ($priceMin !== null && is_numeric($priceMin)) {
            $query->where('price', '>=', (float) $priceMin);
        }
        if ($priceMax !== null && is_numeric($priceMax)) {
            $query->where('price', '<=', (float) $priceMax);
        }

        // Apply sorting
        $query->orderBy($sortBy, $sortDirection);

        // Execute paginated query
        $menus = $query->paginate($limit);

        // Preserve all query parameters in pagination links
        $menus->appends([
            'q' => $searchTerm,  // Pass the actual search term here
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'limit' => $limit,
            'price_min' => $priceMin,
            'price_max' => $priceMax,
        ]);

        return response()->json([
            'message' => 'Menu retrieved successfully',
            'data' => MenuResource::collection($menus)
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMenuRequest $request)
    {
        $menus = Menu::create([
            ...$request->validated(),
            'slug' => Str::slug($request->title),
            'category_id' => $request->category_id,
            'user_id' => Auth::id(),
        ]);

        return response()->json([
            'message' => 'Menu created successfully',
            'data' => new MenuResource($menus)
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return response()->json([
            'message' => 'Menu retrieved successfully',
            'data' => new MenuResource($menu)
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMenuRequest $request, Menu $menu)
    {
        $data = $request->validated();

        if (isset($data['title'])) {
            $data['slug'] = Str::slug($data['title']);
        }

        $menu->update($data);

        return response()->json([
            'message' => 'Menu updated successfully',
            'data' => new MenuResource($menu)
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        return response()->json([
            'message' => 'Menu deleted successfully',
        ]);
    }
}
