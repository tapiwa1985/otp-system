<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\OTPRequest;
use App\Mail\OTPMailer;
use App\Repositories\OTPRepository;
use App\Repositories\UserRepository;
use App\Utilities\OTPGenerator;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class OTPRequestController extends Controller
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
     * OTPController constructor.
     * @param OTPRepository $otpRepository
     * @param UserRepository $userRepository
     */
    public function __construct(OTPRepository $otpRepository, UserRepository $userRepository)
    {
        $this->otpRepository = $otpRepository;
        $this->userRepository = $userRepository;
    }

    /**
     * @param OTPRequest $request
     * @return JsonResponse
     */
    public function store(OTPRequest $request): JsonResponse
    {
        $email = $request->get('email');
        $user = $this->userRepository->getUserByEmail($email);
        $oneTimePIN = $this->generateValidOtpForUser($user->id);
        Mail::to($email)->send(new OTPMailer($oneTimePIN));

        return response()->json(
            [
                'message' => 'OTP sent to email'
            ],
            201
        );
    }

    /**
     * @param int $userId
     * @return string
     */
    private function generateValidOtpForUser(int $userId): string
    {
        $otpWithinWindow = $this->otpRepository->getGeneratedOtpWithinNoResetWindow($userId);
        if ($otpWithinWindow) {
            $newExpiryTime = Carbon::parse($otpWithinWindow->expires_at)->addSeconds(config('values.otp_ttl'));
            $this->otpRepository->refreshOtp($otpWithinWindow->id, $newExpiryTime);
            return $otpWithinWindow->otp;
        }
        $newOtp = $this->generateValidOtpWithinLastDay($userId);
        $this->otpRepository->create($userId, $newOtp, Carbon::now()->addSeconds(config('values.otp_ttl')));

        return $newOtp;
    }


    /**
     * @param int $userId
     * @return string
     */
    private function generateValidOtpWithinLastDay(int $userId): string
    {
        do {
            $oneTimePIN = OTPGenerator::generateOTP();
        } while ($this->otpRepository->isOtpUsedInLastDay($userId, $oneTimePIN));

        return $oneTimePIN;
    }
}
