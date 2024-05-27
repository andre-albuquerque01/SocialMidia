<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserStoreRequest extends FormRequest
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
        $rules = [
            "firstName" => "required|min:3|max:60|regex:/^[^<>]*$/",
            "lastName" => "required|min:3|max:60|regex:/^[^<>]*$/",
            "term_aceite" => "required|regex:/^[^<>]*$/",
            'profileUrl' => 'nullable|image|mimes:jpeg,png,gif,jpg|max:2048',
            'coverPhotoUrl' => 'nullable|image|mimes:jpeg,png,gif,jpg|max:2048',
            "email" => [
                "required",
                "email",
                "max:255",
                "min:2",
                "unique:users",
            ],
            "password" => [
                "required",
                "confirmed",
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            "password_confirmation" => [
                "required",
                Password::min(8)
                    ->mixedCase()
                    ->letters()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
        ];
        if ($this->method() == 'PATCH' || $this->method() == 'PUT') {
            $rules["firstName"] = [
                "nullable",
                "min:3",
                "max:60",
            ];
            $rules["lastName"] = [
                "nullable",
                "min:3",
                "max:60",
            ];
            $rules["term_aceite"] = [
                "nullable"
            ];
            $rules["email"] = [
                "nullable",
                "email",
                "max:255",
                Rule::unique('users', 'email')->ignore($this->user()->idUser, 'idUser'),
            ];
            $rules["password"] = [
                'required',
            ];
            $rules["password_confirmation"] = [
                'nullable',
            ];
        }
        
        return $rules;
    }
}
