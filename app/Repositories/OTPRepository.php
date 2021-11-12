<?php

namespace App\Repositories;

use App\Interfaces\OTPInterface;
use App\Models\OneTimePinModel;
use Carbon\Carbon;

class OTPRepository implements OTPInterface
{
    /**
     * @param int $userId
     * @param string $otp
     * @param $expiryDate
     * @param bool $isUsed
     * @return OneTimePinModel
     */
    public function create(int $userId, string $otp, $expiryDate, bool $isUsed = false): OneTimePinModel
    {
        return OneTimePinModel::create([
            'user_id' => $userId,
            'otp' => $otp,
            'expires_at' => $expiryDate,
            'is_used' => $isUsed
        ]);
    }

    /**
     * @param int $otpId
     * @param $newExpiryTime
     * @return OneTimePinModel|null
     */
    public function refreshOtp(int $otpId, $newExpiryTime): ?OneTimePinModel
    {
        $otp = OneTimePinModel::find($otpId);
        $otp->expires_at = $newExpiryTime;
        $otp->save();

        return $otp;
    }

    /**
     * @param int $userId
     * @return OneTimePinModel|null
     */
    public function getValidOtpForUser(int $userId): ?OneTimePinModel
    {
        return OneTimePinModel::where('user_id', $userId)
            ->where('is_used', false)
            ->where('expires_at', '>=', date('Y-m-d H:i:s'))
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    /**
     * @param int $userId
     * @return mixed
     */
    public function getGeneratedOtpWithinNoResetWindow(int $userId)
    {
        return OneTimePinModel::where('user_id', $userId)
            ->where('is_used', false)
            ->where('created_at', '>=', Carbon::now()->subSeconds(config('values.otp_no_reset_time')))
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    /**
     * @param int $userId
     * @param string $otp
     * @return bool
     */
    public function isOtpUsedInLastDay(int $userId, string $otp): bool
    {
        $searchOtp = OneTimePinModel::where('user_id', $userId)
            ->where('otp', $otp)
            ->where('created_at', '>=', Carbon::now()->now()->subDay())
            ->get();
        return sizeof($searchOtp) > 0;
    }
}
