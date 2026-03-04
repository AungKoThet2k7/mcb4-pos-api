<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Models\Voucher;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Query Initialize
        $query = Voucher::query();

        // Search
        $keyword = $request->q;
        $query->search($keyword);

        // Sort
        $sortColumn = ['id', 'customer_id', 'date', 'total', 'tax', 'net_total', 'cash', 'change', 'voucher_items_count', 'user_id', 'type'];
        $sortBy = in_array($request->sort_by, $sortColumn, true) ? $request->sort_by : 'id';
        $sortDirection = in_array($request->sort_direction, ['asc', 'desc'], true) ? $request->sort_direction : 'desc';

        $query->orderBy($sortBy, $sortDirection);

        // Pagination
        $limit = $request->input('limit', 5);
        $vouchers = $query->orderBy($sortBy, $sortDirection)->paginate($limit)->appends([
            'q' => $keyword,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'limit' => $limit,
        ]);

        return VoucherResource::collection($vouchers)->additional([
            'message' => 'Vouchers retrieved successfully',
        ]);

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVoucherRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVoucherRequest $request, Voucher $voucher)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Voucher $voucher)
    {
        //
    }
}
