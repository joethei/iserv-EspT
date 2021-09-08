<?php

namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use IServ\CoreBundle\Entity\User;

class TeacherGroupRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, TeacherGroup::class);
    }

    /**
     * @param mixed $id
     * @param null $lockMode
     * @param null $lockVersion
     * @return int|mixed|object|string|null
     * @throws NonUniqueResultException
     */
    public function find($id, $lockMode = null, $lockVersion = null) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\TeacherGroup s WHERE s.id = :id");
        $query->setParameter('id', $id);

        return $query->getOneOrNullResult();
    }

    /**
     * @return TeacherGroup[]
     */
    public function findFor(User $user): array {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\TeacherGroup s WHERE :user MEMBER OF s.users");
        $query->setParameter('user', $user);

        return $query->getResult();
    }

    /**
     * @return TeacherGroup[]
     */
    public function findAll(): array {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\TeacherGroup s");

        return $query->getResult();
    }

}