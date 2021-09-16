<?php

namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

class EventTypeRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, EventType::class);
    }

    /**
     * @param string $name
     * @return EventType
     * @throws NonUniqueResultException
     */
    public function findFor(string $name): EventType {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\EventType s WHERE s.name = :name");
        $query->setParameter("name", $name);

        return $query->setMaxResults(1)->getOneOrNullResult();
    }

}