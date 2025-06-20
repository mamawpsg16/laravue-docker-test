<?php

namespace App\Services;

use App\Contracts\UserContext;
use App\Models\User;

class HardCodedUserContext implements UserContext
{
    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
