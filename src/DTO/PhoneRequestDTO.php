<?php

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class PhoneRequestDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'Phone number is required')]
        #[Assert\Regex(
            pattern: '/^\+?\d{10,15}$/',
            message: 'Invalid phone number format'
        )]
        public readonly string $phone
    ) {}
}