<?php

namespace App\Http\Requests;

use App\Enums\DeliveryStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DeliveryRequest extends FormRequest
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
            'driver_id' => ['required', Rule::exists('drivers', 'id')],
            'status' => ['required', Rule::enum(DeliveryStatusEnum::class)],
            'delivery_note' => ['nullable', 'string'],
            'delivered_at' => ['nullable', Rule::date()],
            'cancelled_at' => ['nullable', Rule::date()],
        ];
    }
}
