<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DriverRequest extends FormRequest
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
            'user_id' => ['required', Rule::exists('users', 'id')],
            'vehicle_make' => ['required', 'string', 'max:190'],
            'vehicle_model' => ['required', 'string', 'max:190'],
            'vehicle_colour' => ['required', 'string', 'max:50'],
            'vehicle_registration_number' => ['required', 'string', Rule::unique('drivers', 'vehicle_registration_number')->ignore(optional($this->route('driver'))->id)],
            'driver_license_expires_at' => ['required', Rule::date()->after('6 months')],
        ];
    }
}
