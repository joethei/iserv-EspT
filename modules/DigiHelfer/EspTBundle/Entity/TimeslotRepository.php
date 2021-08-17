<?php

namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TimeslotRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry, $entityClass) {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function find($id, $lockMode = null, $lockVersion = null) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\Timeslot s WHERE id=$id");

        return $query->getOneOrNullResult();
    }

    public function findAll() {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\Timeslot s");

        return $query->getArrayResult();
    }

    /**
     * @throws \Doctrine\DBAL\Exception
     */
    public function clear() {
        //based on https://blog.nevercodealone.de/symfony-doctrine-truncate-table/
        $connection = parent::getEntityManager()->getConnection();
        $platform   = $connection->getDatabasePlatform();

        $connection->executeStatement($platform->getTruncateTableSQL('espt_timeslot', true));
    }

}