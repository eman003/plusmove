<?php

namespace App\Http\Requests;

use App\Enums\DeliveryStatusEnum;
use App\Models\V1\Customer;
use App\Models\V1\Package;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class PackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', Package::class);
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
            'address_id' => ['required', Rule::exists('addresses', 'id')],
            'delivery_note' => ['nullable', 'string'],
            'scheduled_for' => ['required', Rule::date()->afterOrEqual('today')],
        ];

        if ($this->method() === 'PATCH' || $this->method() === 'PUT') {
            $fieldsValidation['status'] = ['required', Rule::enum(DeliveryStatusEnum::class)];
        }

        return $fieldsValidation;
    }
}
