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
        // search params
        $searchTerm = $request->get('q');
        $priceMin = $request->get('price_min');
        $priceMax = $request->get('price_max');

        // sort params
        $validSortColumns = ['id', 'title', 'price'];
        $sortBy = in_array($request->input('sort_by'), $validSortColumns, true) ? $request->input('sort_by') : 'id';
        $sortDirection = in_array($request->input('sort_direction'), ['asc', 'desc'], true) ? $request->input('sort_direction') : 'desc';

        $menus = Menu::query()
            ->where('user_id', Auth::id())
            ->with('category');

        // search filter
        if ($searchTerm) {
            $menus->where(function ($q) use ($searchTerm) {
                $q->where('title', 'like', "%{$searchTerm}%")
                    ->orWhere('slug', 'like', "%{$searchTerm}%")
                    ->orWhereHas('category', function ($cat) use ($searchTerm) {
                        $cat->where('title', 'like', "%{$searchTerm}%")
                            ->orWhere('slug', 'like', "%{$searchTerm}%");
                    });
            });
        }

        // price filters
        if ($priceMin !== null && is_numeric($priceMin)) {
            $menus->where('price', '>=', (float)$priceMin);
        }
        if ($priceMax !== null && is_numeric($priceMax)) {
            $menus->where('price', '<=', (float)$priceMax);
        }

        // paginate
        $menus = $menus->orderBy($sortBy, $sortDirection)->paginate($request->input('limit', 5));

        return response()->json([
            'message' => 'Menu list retrieved successfully',
            'data' => MenuResource::collection($menus),
            'meta' => [
                'current_page' => $menus->currentPage(),
                'per_page' => $menus->perPage(),
                'total' => $menus->total(),
                'last_page' => $menus->lastPage(),
            ]
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
