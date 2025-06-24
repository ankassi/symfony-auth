<?php

namespace App\Enum;

enum ErrorMessageEnum: string
{
    case USER_NOT_FOUND = 'User not found';
    case INVALID_CODE = 'Invalid code';
    case TOO_MANY_REQUESTS = 'Too many requests. Try again later.';
    case BLOCKED = 'Too many requests. You have been blocked for 1 hour.';
}