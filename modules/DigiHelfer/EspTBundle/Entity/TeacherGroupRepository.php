<?php

namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TeacherGroupRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry, $entityClass) {
        parent::__construct($registry, $entityClass);
    }

    /**
     * @param mixed $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return int|mixed|object|string|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function find($id, $lockMode = null, $lockVersion = null) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\TeacherGroup s WHERE id=$id");

        return $query->getOneOrNullResult();
    }

    /**
     * @return TeacherGroup[]
     */
    public function findAll() {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\TeacherGroup s");

        return $query->getArrayResult();
    }

}