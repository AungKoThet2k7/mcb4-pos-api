<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBannerRequest;
use App\Http\Requests\UpdateBannerRequest;
use App\Http\Resources\BannerResource;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Banner::query();

        // Search
        $keyword = $request->q;
        $query->search($keyword);

        // Sorting
        $sortColumn = ['id', 'created_at'];
        $sortBy = in_array($request->sort_by, $sortColumn, true) ? $request->sort_by : 'id';
        $sortDirection = in_array($request->sort_direction, ['asc', 'desc'], true) ? $request->sort_direction : 'desc';

        $query->orderBy($sortBy, $sortDirection);

        // Filter
        $query->when($request->boolean('only_available'), function ($q) {
            $q->where(function ($q) {
                $q->where('is_available', true);
            });
        });

        // Pagination
        $limit = $request->input('limit', 5);
        $banners = $query->orderBy($sortBy, $sortDirection)->paginate($limit)->appends([
            'q' => $keyword,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'only_available' => $onlyAvailable,
            'limit' => $limit,
        ]);

        return BannerResource::collection($banners)->additional([
            'message' => 'Banner retrieved successfully',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBannerRequest $request)
    {
        $banner = Banner::create([...$request->validated(), 'user_id' => Auth::id()]);

        return response()->json([
            'message' => 'Banner created successfully',
            'data' => new BannerResource($banner),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        return response()->json([
            'message' => 'Banner retrieved successfully',
            'data' => new BannerResource($banner),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBannerRequest $request, Banner $banner)
    {
        $banner->update($request->validated());

        return response()->json([
            'message' => 'Banner updated successfully',
            'data' => new BannerResource($banner),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        $banner->delete();

        return response()->json([
            'message' => 'Banner deleted successfully',
        ]);
    }
}
