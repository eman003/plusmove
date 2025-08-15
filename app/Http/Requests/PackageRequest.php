<?php

namespace App\Http\Requests;

use App\Enums\DeliveryStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PackageRequest extends FormRequest
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
            'customer_id' => ['required', Rule::exists('customers', 'id')],
            'status' => ['required', Rule::enum(DeliveryStatusEnum::class)],
            'delivery_note' => ['nullable', 'string'],
        ];
    }
}
