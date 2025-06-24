<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserVerificationRequest;
use ContainerMxCtizI\get_Console_Command_ConfigDumpReference_LazyService;
use Doctrine\Persistence\ManagerRegistry;

class VerificationCodeRepository extends AbstractRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserVerificationRequest::class);
    }

    public function save(UserVerificationRequest $data): void
    {
        $this->getEntityManager()->persist($data);
        $this->getEntityManager()->flush();
    }

    public function all(): array
    {
        return $this->findBy([]);
    }

    public function findOneByFilters(array $filters = []): ?UserVerificationRequest
    {
        $qb = $this->createQueryBuilder('v');

        foreach ($filters as $field => $value) {
            $qb->andWhere("v.$field = :$field")
                ->setParameter($field, $value);
        }

        $qb->orderBy('v.sentAt', 'DESC')
            ->setMaxResults(1);

        return $qb->getQuery()->getOneOrNullResult();
    }
}