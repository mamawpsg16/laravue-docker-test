<?php

namespace App\Contracts;

use App\Models\User;

interface UserContext
{
    public function getUser(): User;
}
