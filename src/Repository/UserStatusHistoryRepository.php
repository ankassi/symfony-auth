<?php

namespace App\Repository;

use App\Entity\UserStatusHistory;
use Doctrine\Persistence\ManagerRegistry;

class UserStatusHistoryRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserStatusHistory::class);
    }

    public function save(UserStatusHistory $data): void
    {
        $this->getEntityManager()->persist($data);
        $this->getEntityManager()->flush();
    }
}