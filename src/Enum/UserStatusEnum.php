<?php

namespace App\Enum;

enum UserStatusEnum: string
{
    case REGISTERED = 'registered';
    case VERIFIED = 'verified';
    case BLOCKED = 'blocked';
}