<?php

namespace App\Http\Requests;

use App\Enums\DeliveryStatusEnum;
use App\Models\V1\Customer;
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
        $fieldsValidation = [
            'customer_id' => ['required', Rule::exists('customers', 'id')],
            'address_id' => [
                'required',
                Rule::exists('addresses', 'id')
                ->where(
                    fn($query) => $query->where('addressable_type', Customer::class)
                    ->where('addressable_id', $this->input('customer_id'))
                )
            ],
            //'driver_id' => ['nullable', Rule::exists('drivers', 'id')], The system will assign the driver automatically
            'delivery_note' => ['nullable', 'string'],
            'scheduled_for' => ['required', Rule::date()->afterOrEqual('today')],
        ];

        if ($this->method() === 'PATCH' || $this->method() === 'PUT') {
            $fieldsValidation['status'] = ['required', Rule::enum(DeliveryStatusEnum::class)];
        }

        return $fieldsValidation;
    }
}
