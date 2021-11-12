<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    /**
     * @param string $email
     * @return User|null
     */
    public function getUserByEmail(string $email): ?User
    {
        return User::where('email', $email)->first();
    }
}
