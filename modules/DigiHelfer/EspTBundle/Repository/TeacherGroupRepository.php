<?php

namespace DigiHelfer\EspTBundle\Repository;

use DigiHelfer\EspTBundle\Entity\TeacherGroup;
use DigiHelfer\EspTBundle\Entity\TeacherGroupSelection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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
    public function find($id, $lockMode = null, $lockVersion = null) : TeacherGroup {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\TeacherGroup s WHERE s.id = :id");
        $query->setParameter('id', $id);

        return $query->getOneOrNullResult();
    }

    /**
     * @return Collection|TeacherGroup
     */
    public function findFor(User $user): Collection {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\TeacherGroup s WHERE :user MEMBER OF s.users");
        $query->setParameter('user', $user);

        return new ArrayCollection($query->getResult());
    }

    /**
     * @return Collection|TeacherGroup
     */
    public function findAll(): Collection {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\TeacherGroup s ORDER BY s.room");

        return new ArrayCollection($query->getResult());
    }

    /**
     * return TeacherGroupSelection
     * @throws NonUniqueResultException
     */
    public function findForSelection(User $user) : ?TeacherGroupSelection {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\TeacherGroupSelection s WHERE s.user = :user");
        $query->setParameter('user', $user);

        return $query->getOneOrNullResult();
    }

}