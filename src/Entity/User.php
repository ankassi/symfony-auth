<?php

namespace App\Entity;

use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity]
#[ORM\Table(name: "users")]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(type: "string", length: 255)]
    private string $userName;

    #[ORM\Column(type: "string", length: 20, unique: true)]
    private string $phoneNumber;

    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $registeredAt;

    #[ORM\OneToMany(targetEntity: UserStatusHistory::class, mappedBy: "user")]
    private Collection $statuses;

    #[ORM\OneToMany(targetEntity: UserVerificationRequest::class, mappedBy: "user", cascade: ["persist", "remove"])]
    private Collection $verificationCodes;

    public function __construct()
    {
        $this->statuses = new ArrayCollection();
        $this->verificationCodes = new ArrayCollection();
        $this->registeredAt = new DateTimeImmutable();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUserName(): string
    {
        return $this->userName;
    }

    public function setUserName(string $userName): self
    {
        $this->userName = $userName;
        return $this;
    }

    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    public function getRegisteredAt(): DateTimeInterface
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(DateTimeInterface $registeredAt): self
    {
        $this->registeredAt = $registeredAt;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getStatuses(): Collection
    {
        return $this->statuses;
    }

    public function addStatus(UserStatusHistory $status): self
    {
        if (!$this->statuses->contains($status)) {
            $this->statuses[] = $status;
            $status->setUser($this);
        }
        return $this;
    }

    public function getCurrentStatus(): ?UserStatusHistory
    {
        return $this->statuses->last() ?: null;
    }

    /**
     * @return Collection
     */
    public function getVerificationCodes(): Collection
    {
        return $this->verificationCodes;
    }

    public function addVerificationCode(UserVerificationRequest $code): self
    {
        if (!$this->verificationCodes->contains($code)) {
            $this->verificationCodes[] = $code;
            $code->setUser($this);
        }
        return $this;
    }

    public function removeVerificationCode(UserVerificationRequest $code): self
    {
        if ($this->verificationCodes->removeElement($code)) {
            if ($code->getUser() === $this) {
                $code->setUser(null);
            }
        }
        return $this;
    }
}
