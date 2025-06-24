<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use App\DTO\PhoneRequestDTO;


class VerifyCodeRequestDTO extends PhoneRequestDTO
{
    #[Assert\Valid]
    public PhoneRequestDTO $phoneDTO;

    public function __construct(
        string $phone,
        #[Assert\NotBlank(message: 'Code is required')]
        #[Assert\Regex(
            pattern: '/^\d{4,6}$/',
            message: 'Code must be 4–6 digits'
        )]
        public readonly string $code
    ) {
        parent::__construct($phone);
    }
}