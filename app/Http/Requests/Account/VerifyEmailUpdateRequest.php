<?php

namespace App\Http\Requests\Account;

use App\Models\UserEmailUpdate;
use Illuminate\Foundation\Http\FormRequest;

class VerifyEmailUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $pendingUpdate = UserEmailUpdate::findOrFail($this->route('id'));

        if (!hash_equals((string)$pendingUpdate->getKey(), (string)$this->route('id'))) {
            return false;
        }

        if (!hash_equals(sha1($pendingUpdate->getVerificationToken()), (string)$this->route('token'))) {
            return false;
        }

        if (!hash_equals(sha1($pendingUpdate->getEmailForVerification()), (string)$this->route('hash'))) {
            return false;
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
