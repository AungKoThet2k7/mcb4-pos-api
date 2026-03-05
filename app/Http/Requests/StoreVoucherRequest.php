<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreVoucherRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [    // TODO: apply 'exists:customers,id' rule after customer module is completed
            'customer_id' => ['nullable'],
            'date' => ['required', 'date'],
            'cash' => ['required', 'integer', 'min:0'],
            'change' => ['required', 'integer', 'min:0'],
            'type' => ['required', Rule::in(config('base.sale_type'))],
            'voucher_items' => ['required', 'array', 'min:1'],
            'voucher_items.*.menu_id' => ['required', 'exists:menus,id'],
            'voucher_items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }
}
