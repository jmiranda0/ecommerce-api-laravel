<?php

namespace App\Http\Requests\Api\Order;

use App\Enums\PaymentMethod;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
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
        return [
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'zip_code' => 'required|string|max:10',
            'payment_method' => [
                    'required', 
                    'string', 
                    Rule::in(PaymentMethod::getValues())
                ],
            'items' => 'required|array|min:1|max:50',
            'items.*.id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:100',
        ];
    }
    public function messages(): array
    {
        return [
            'items.required' => 'Debe incluir al menos un producto',
            'items.*.id.exists' => 'Uno de los productos no existe',
            'items.*.quantity.min' => 'La cantidad mínima es 1',
        ];
    }
}
