<?php

namespace App\Exports;

use App\Models\Voucher;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class VouchersExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        // Query Initialize
        $query = Voucher::query();

        // Search
        $keyword = $this->request->q;
        $query->search($keyword);

        // Date filter
        $query->dateFilter(
            $this->request->start_date,
            $this->request->end_date
        );

        // Order type filter
        $type = $this->request->order_type;
        $query->when($type && in_array($type, config('base.sale_types')), function ($q) use ($type) {
            $q->where('type', $type);
        });

        return $query;
    }

    public function map($voucher): array
    {
        return [
            $voucher->invoice_number,
            $voucher->customer_id,
            $voucher->date,
            $voucher->total,
            $voucher->tax,
            $voucher->net_total,
            $voucher->type,
            optional($voucher->user)->name,
            $voucher->voucherItems->pluck('menu.title')->implode(', '),
        ];
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Customer',
            'Date',
            'Total',
            'Tax',
            'Net Total',
            'Order Type',
            'Cashier',
            'Menus',
        ];
    }
}
