<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// Personal data of a user: used by the profile form and by the admin user form.
class UserDataRequest extends FormRequest
{
    public function rules(): array
    {
        $user = $this->route('user') ?? $this->user();

        return [
            'name' => 'required|string|max:20|alpha',
            'last_name' => 'required|string|max:30|alpha',
            'email' => ['required', 'email', 'max:100', Rule::unique('users', 'email')->ignore($user->id)],
            'phone_number' => 'required|string|max:15|regex:/^[0-9]+$/',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required.',
            'name.alpha' => 'Name can only contain letters.',
            'name.max' => 'Name can be at most 20 characters.',
            'last_name.required' => 'Last Name is required.',
            'last_name.alpha' => 'Last Name can only contain letters.',
            'last_name.max' => 'Last Name can be at most 30 characters.',
            'email.required' => 'E-mail is required.',
            'email.email' => 'Please provide a valid E-mail address.',
            'email.unique' => 'This E-mail is already in use.',
            'phone_number.required' => 'Phone number is required.',
            'phone_number.regex' => 'Phone number can only contain digits.',
            'phone_number.max' => 'Phone number can be at most 15 digits.',
        ];
    }
}
