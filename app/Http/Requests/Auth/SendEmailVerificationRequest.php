<?php

namespace App\Http\Requests\Auth;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class SendEmailVerificationRequest extends FormRequest
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
            'id' => [
                'required',
                'exists:users,id',
                /*Rule::exists('users')->where(function ($query) {
                    $query->whereNull('email_verified_at');
                }),*/
            ],
            'email' => 'required|email',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = User::find($this->input('id'));

            if ($user && $user->hasVerifiedEmail()) {
                $validator->errors()->add('id', 'The email address has already been verified!');
            }
        });
    }
}
