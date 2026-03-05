<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VoucherResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'invoice_number' => $this->invoice_number,
            'customer_id' => $this->customer_id ?? null,
            'date' => $this->date,
            'total' => $this->total,
            'tax' => $this->tax,
            'net_total' => $this->net_total,
            'cash' => $this->cash,
            'change' => $this->change,
            'voucher_items_count' => $this->voucher_items_count,
            'type' => $this->type,
            'user_id' => $this->user_id,
            'voucher_items' => VoucherItemResource::collection($this->voucherItems),
            'created_at' => $this->created_at?->format('j-m-Y H:i:s'),
            'updated_at' => $this->updated_at?->format('j-m-Y H:i:s'),
        ];
    }
}
