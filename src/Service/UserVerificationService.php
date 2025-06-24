<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\UserStatusHistory;
use App\Entity\UserVerificationRequest;
use App\Enum\UserStatusEnum;
use App\Enum\SuccessMessageEnum;
use App\Enum\ErrorMessageEnum;
use App\Repository\UserRepository;
use App\Repository\UserStatusHistoryRepository;
use App\Repository\VerificationCodeRepository;
use DateTimeImmutable;
use Psr\Cache\InvalidArgumentException;
use Random\RandomException;

class UserVerificationService
{
    private int $timeInterval = 60;
    private int $codeLength = 4;

    public function __construct(
        private readonly UserRepository                 $userRepository,
        private readonly VerificationCodeRepository     $verificationCodeRepository,
        private readonly UserStatusHistoryRepository    $statusHistoryRepository,
        private readonly LimitService                   $limitService,
    ){}

    /**
     * @throws RandomException
     * @throws InvalidArgumentException
     */
    public function requestCode(string $phone): array
    {
        $user = $this->findOrCreateUser($phone);

        if ($this->isUserBlocked($phone)) {
            return $this->formatResponse($user, ErrorMessageEnum::TOO_MANY_REQUESTS->value);
        }

        if (!$this->limitService->checkRequestLimit($phone)) {
            $this->blockUser($user, $phone);
            return $this->formatResponse($user, ErrorMessageEnum::BLOCKED->value);
        }

        $lastCode = $this->verificationCodeRepository
            ->findOneByFilters(['user' => $user]);

        if ($this->wasCodeSentRecently($lastCode)) {
            return $this->formatResponse($user, SuccessMessageEnum::CODE_ALREADY_SENT->value, $lastCode->getCode());
        }

        $code = $this->generateCode();
        $this->createVerificationCode($user, $code);

        return $this->formatResponse($user, SuccessMessageEnum::CODE_SENT->value, $code);
    }

    public function verifyCode(string $phone, string $code): array
    {
        $user = $this->userRepository
            ->setFilter(['phoneNumber' => $phone])
            ->findByFilters();

        if (!$user) {
            return ['error' => ErrorMessageEnum::USER_NOT_FOUND->value];
        }

        $codeExist = $this->verificationCodeRepository
            ->findOneByFilters([
                'user' => $user,
                'code' => $code
            ]);

        if ($codeExist) {
            $this->createStatus($user, UserStatusEnum::VERIFIED);
            return $this->formatResponse($user, SuccessMessageEnum::VERIFICATION_SUCCESSFUL->value, $code);
        }

        return ['error' => ErrorMessageEnum::INVALID_CODE->value];
    }

    /**
     * @throws RandomException
     */
    private function generateCode(): string
    {
        return str_pad(random_int(0, 9999), $this->codeLength, '0', STR_PAD_LEFT);
    }

    private function findOrCreateUser(string $phone): User
    {
        $user = $this->userRepository
            ->setFilter(['phoneNumber' => $phone])
            ->findByFilters();

        return $user ?? $this->createUser($phone);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function isUserBlocked(string $phone): bool
    {
        return $this->limitService->isBlocked($phone);
    }

    /**
     * @throws InvalidArgumentException
     */
    private function blockUser(User $user, string $phone): void
    {
        $this->limitService->blockUser($phone);
        $this->createStatus($user, UserStatusEnum::BLOCKED);
    }

    private function wasCodeSentRecently(?UserVerificationRequest $lastCode): bool
    {
        if (!$lastCode) {
            return false;
        }

        $now = new DateTimeImmutable();
        $interval = $now->getTimestamp() - $lastCode->getSentAt()->getTimestamp();

        return $interval < $this->timeInterval;
    }

    private function createUser(string $phone): User
    {
        $user = new User();
        $user->setPhoneNumber($phone);
        $user->setRegisteredAt(new DateTimeImmutable());
        $user->setUserName('test');
        $this->userRepository->save($user);

        $this->createStatus($user, UserStatusEnum::REGISTERED);

        return $user;
    }

    private function createStatus(User $user, UserStatusEnum $status): void
    {
        $userStatus = new UserStatusHistory();
        $userStatus->setUser($user);
        $userStatus->setStatus($status);
        $this->statusHistoryRepository->save($userStatus);
    }

    private function createVerificationCode(User $user, string $code): void
    {
        $verification = new UserVerificationRequest();
        $verification->setUser($user);
        $verification->setCode($code);
        $this->verificationCodeRepository->save($verification);
    }

    private function formatResponse(User $user, string $message, string $code = ''): array
    {
        return [
            'message' => $message,
            'userId' => $user->getId(),
            'phone' => $user->getPhoneNumber(),
            'code' => $code,
        ];
    }
}
