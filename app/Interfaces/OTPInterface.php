<?php

namespace App\Interfaces;

use App\Models\OneTimePinModel;

interface OTPInterface
{
    /**
     * @param int $userId
     * @param string $otp
     * @param $expiryDate
     * @param bool $isUsed
     * @return OneTimePinModel
     */
    public function create(int $userId, string $otp, $expiryDate, bool $isUsed = false): OneTimePinModel;

    /**
     * @param int $otpId
     * @param $newExpiryTime
     * @return OneTimePinModel|null
     */
    public function refreshOtp(int $otpId, $newExpiryTime): ?OneTimePinModel;

    /**
     * @param int $userId
     * @return OneTimePinModel|null
     */
    public function getValidOtpForUser(int $userId): ?OneTimePinModel;

    /**
     * @param int $userId
     * @return mixed
     */
    public function getGeneratedOtpWithinNoResetWindow(int $userId);

    /**
     * @param int $userId
     * @param string $otp
     * @return bool
     */
    public function isOtpUsedInLastDay(int $userId, string $otp): bool;
}
