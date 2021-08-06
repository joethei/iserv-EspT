<?php

namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TimeslotRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry, $entityClass) {
        parent::__construct($registry, $entityClass);
    }

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

}