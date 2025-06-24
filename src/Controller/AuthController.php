<?php

namespace App\Controller;

use App\DTO\PhoneRequestDTO;
use App\DTO\VerifyCodeRequestDTO;
use App\Service\UserVerificationService;
use Psr\Cache\InvalidArgumentException;
use Random\RandomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    public function __construct(
        private readonly UserVerificationService $userVerificationService,
    ){}

    /**
     * @throws RandomException|InvalidArgumentException
     */
    #[Route(path: '/api/request-code', name: 'request_code', methods: ['POST'])]
    public function requestCode(
        #[MapRequestPayload(validationFailedStatusCode: 400)] PhoneRequestDTO $phoneRequestDTO,
    ): JsonResponse {

        $response = $this->userVerificationService->requestCode($phoneRequestDTO->phone);

        return $this->json($response);
    }


    #[Route(path: '/api/verify-code', name: 'verify_code', methods: ['POST'])]
    public function verifyCode(
        #[MapRequestPayload(validationFailedStatusCode: 400)] VerifyCodeRequestDTO $dto
    ): JsonResponse
    {
        $response = $this->userVerificationService->verifyCode($dto->phone, $dto->code);

        return $this->json($response);
    }
}
