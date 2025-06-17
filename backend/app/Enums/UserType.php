<?php

namespace App\Enums;

enum UserType: string
{
    case Admin = 'admin';
    case Provider = 'provider';
    case Client = 'client';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}