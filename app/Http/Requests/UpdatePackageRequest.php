<?php

namespace App\Http\Requests;

use App\Enums\DeliveryStatusEnum;
use App\Models\V1\Customer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UpdatePackageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('update', $this->route('package'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'address_id' => ['required', Rule::exists('addresses', 'id')],
            'delivery_note' => ['nullable', 'string'],
            'scheduled_for' => ['sometimes', 'required', Rule::date()->afterOrEqual('today')],
            'status' => ['required', Rule::enum(DeliveryStatusEnum::class)]
        ];
    }
}
