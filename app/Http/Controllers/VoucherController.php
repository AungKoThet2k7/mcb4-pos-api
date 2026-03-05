<?php

namespace App\Http\Controllers;

use App\Exports\VouchersExport;
use App\Http\Requests\StoreVoucherRequest;
use App\Http\Requests\UpdateVoucherRequest;
use App\Http\Resources\VoucherResource;
use App\Models\Menu;
use App\Models\Voucher;
use App\Models\VoucherItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

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

        // Filter by date
        $startDate = $request->start_date;
        $endDate = $request->end_date;
        $query->dateFilter($startDate, $endDate);

        // Filter by order type
        $type = $request->order_type;
        $query->when($type && in_array($type, config('base.sale_types')), function ($q) use ($type) {
            $q->where('type', $type);
        });

        // Pagination
        $limit = $request->input('limit', 5);
        $vouchers = $query->orderBy($sortBy, $sortDirection)->paginate($limit)->appends([
            'q' => $keyword,
            'sort_by' => $sortBy,
            'sort_direction' => $sortDirection,
            'start_date' => $startDate,
            'end_date' => $endDate,
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
        $validated = $request->validated();

        try {

            DB::beginTransaction();

            $menuIds = collect($validated['voucher_items'])->pluck('menu_id');
            $menus = Menu::whereIn('id', $menuIds)->get();

            // voucher items array
            $voucherItems = [];

            foreach ($validated['voucher_items'] as $item) {
                $menu = $menus->firstWhere('id', $item['menu_id']);
                $cost = $menu->price * $item['quantity'];

                $voucherItems[] = [
                    'menu_id' => $item['menu_id'],
                    'menu' => $menu,
                    'quantity' => $item['quantity'],
                    'price' => $menu->price,
                    'cost' => $cost,
                    'user_id' => Auth::id(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            $total = collect($voucherItems)->sum('cost');
            $tax = $total * 0.07;
            $net_total = $total + $tax;

            // Create a new voucher
            $voucher = Voucher::create([
                'invoice_number' => $validated['invoice_number'],
                'customer_id' => $validated['customer_id'] ?? null,
                'date' => $validated['date'],
                'total' => $total,
                'tax' => $tax,
                'net_total' => $net_total,
                'cash' => $validated['cash'],
                'change' => $validated['change'],
                'voucher_items_count' => count($voucherItems),
                'type' => $validated['type'],
                'user_id' => Auth::id(),
            ]);

            // Store voucher items
            $voucherItems = collect($voucherItems)->map(function ($item) use ($voucher) {
                $item['voucher_id'] = $voucher->id;

                return $item;
            })->toArray();

            VoucherItem::insert($voucherItems);

            DB::commit();

            return response()->json([
                'message' => 'Voucher created successfully',
                'data' => new VoucherResource($voucher),
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to create voucher',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Voucher $voucher)
    {
        return response()->json([
            'message' => 'Voucher retrieved successfully',
            'data' => new VoucherResource($voucher),
        ]);
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

    public function export(Request $request)
    {
        return Excel::download(new VouchersExport($request), 'vouchers.xlsx');
    }
}
