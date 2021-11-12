<?php

namespace App\Http\Requests;

class OTPVerificationRequest extends ApiRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['required', 'email', 'exists:users'],
            'otp' => ['required', 'numeric', 'min:6']
        ];
    }
}
