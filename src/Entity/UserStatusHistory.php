<?php

namespace App\Entity;

use App\Enum\UserStatusEnum;
use DateTimeImmutable;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: "user_status_history")]
class UserStatusHistory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private int $id;

    #[ORM\Column(enumType: UserStatusEnum::class)]
    private UserStatusEnum $status;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: "statuses")]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user;

    #[ORM\Column(type: "datetime")]
    private DateTimeInterface $changedAt;

   public function __construct()
   {
       $this->changedAt = new DateTimeImmutable();
   }

    public function getId(): int
    {
        return $this->id;
    }

    public function getStatus(): UserStatusEnum
    {
        return $this->status;
    }

    public function setStatus(UserStatusEnum $status): self
    {
        $this->status = $status;
        return $this;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function getChangedAt(): DateTimeInterface
    {
        return $this->changedAt;
    }

    public function setChangedAt(DateTimeInterface $changedAt): self
    {
        $this->changedAt = $changedAt;
        return $this;
    }
}
