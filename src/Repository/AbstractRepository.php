<?php
namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<User>
 */
abstract class AbstractRepository extends ServiceEntityRepository {

    public array $filter = [];
    public array $order = [];

    public function setFilter(array $filter): self
    {
        $this->filter = array_merge($this->filter, $filter);
        return $this;
    }

    public function setOrder(array $order): self
    {
        $this->order = array_merge($this->order, $order);
        return $this;
    }

    public function remove(object $entity): void
    {
        $this->getEntityManager()->remove($entity);
        $this->getEntityManager()->flush();
    }
}