<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('createUserAddress', $this->route('user'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['nullable', 'string', 'max:190'],
            'address_line_1' => ['required', 'string', 'max:190'],
            'address_line_2' => ['nullable', 'string', 'max:190'],
            'suburb' => ['nullable', 'string', 'max:190'],
            'city' => ['nullable', 'string', 'max:190'],
            'province' => ['nullable', 'string', 'max:190'],
            'postal_code' => ['nullable', 'string', 'max:190'],
            'country' => ['nullable', 'string', 'max:190'],
        ];
    }
}
