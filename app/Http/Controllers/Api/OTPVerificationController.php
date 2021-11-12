<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OTPVerificationRequest;
use App\Repositories\OTPRepository;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;

class OTPVerificationController extends Controller
{
    /**
     * @var OTPRepository
     */
    protected $otpRepository;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * VerificationController constructor.
     * @param OTPRepository $otpRepository
     * @param UserRepository $userRepository
     */
    public function __construct(OTPRepository $otpRepository, UserRepository $userRepository)
    {
        $this->otpRepository = $otpRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param OTPVerificationRequest $request
     * @return JsonResponse
     */
    public function verify(OTPVerificationRequest $request): JsonResponse
    {
        $email = $request->get('email');
        $otp = $request->get('otp');
        $user = $this->userRepository->getUserByEmail($email);
        $correctOTPQuery = $this->otpRepository->getValidOtpForUser($user->id);
        if ($correctOTPQuery) {
            if ($correctOTPQuery->otp == $otp) {
                $correctOTPQuery->is_used = true;
                $correctOTPQuery->save();
                return response()->json(
                    [
                        'message' => 'Verification successful'
                    ],
                    200
                );
            }
        }
        return response()->json(
            [
                'message' => 'Verification unsuccessful'
            ],
            401
        );
    }
}
