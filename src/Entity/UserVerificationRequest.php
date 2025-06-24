<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "user_verification_requests")]
class UserVerificationRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "verificationCodes")]
    #[ORM\JoinColumn(nullable: false, onDelete: "CASCADE")]
    private User $user;

    #[ORM\Column(type: "string", length: 10)]
    private string $code;

    #[ORM\Column(type: "datetime_immutable")]
    private DateTimeInterface $sentAt;

    public function __construct()
    {
        $this->sentAt = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;
        return $this;
    }

    public function getSentAt(): DateTimeInterface
    {
        return $this->sentAt;
    }

    public function setSentAt(DateTimeInterface $sentAt): self
    {
        $this->sentAt = $sentAt;
        return $this;
    }
}
