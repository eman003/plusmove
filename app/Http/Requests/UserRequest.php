<?php

namespace App\Http\Requests;

use App\Models\V1\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Gate::allows('create', User::class) || Gate::allows('update', $this->route('user'));
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'first_name' => ['required', 'string', 'max:190'],
            'last_name' => ['required', 'string', 'max:190'],
            'phone_number' => ['required', 'string', 'min:10', 'max:15'],
            'email' => ['required', 'email', 'max:190', Rule::unique('users', 'email')->ignore(optional($this->route('user'))->id)],
            'role_id' => ['required', Rule::exists('roles', 'id')],
        ];

        if (($this->method() === 'PATCH' || $this->method() === 'PUT') && auth()->user()->isDriver()) {
            unset($rules['role_id']);
        }

        return $rules;
    }
}
