<?php

namespace App\Enum;

enum SuccessMessageEnum: string
{
    case CODE_SENT = 'Code sent';
    case CODE_ALREADY_SENT = 'Code already sent (within 1 minute)';
    case VERIFICATION_SUCCESSFUL = 'Verification successful';
}