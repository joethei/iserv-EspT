<?php

namespace DigiHelfer\EspTBundle\Repository;

use DigiHelfer\EspTBundle\Entity\Timeslot;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;
use IServ\CoreBundle\Entity\User;

class TimeslotRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Timeslot::class);
    }

    /**
     * @throws NonUniqueResultException
     */
    public function find($id, $lockMode = null, $lockVersion = null) : Timeslot {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\Timeslot s WHERE s.id = :id");
        $query->setParameter('id', $id);

        return $query->getOneOrNullResult();
    }

    /**
     * @return Collection
     */
    public function findAll() : Collection {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\Timeslot s ORDER BY s.start ASC");

        return new ArrayCollection($query->getResult());
    }

    /**
     * @param User $user
     * @return Collection|Timeslot
     */
    public function findForTeacher(User $user) : Collection {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\Timeslot s JOIN s.group g WHERE :user MEMBER OF g.users ORDER BY s.start ASC");
        $query->setParameter('user', $user);

        return new ArrayCollection($query->getResult());
    }

    /**
     * @param User $user
     * @return Collection|Timeslot
     */
    public function findForUser(User $user) : Collection {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\Timeslot s WHERE s.user = :user ORDER BY s.start ASC");
        $query->setParameter('user', $user);

        return new ArrayCollection($query->getResult());
    }

    /**
     * @param TeacherGroup $group
     * @return Collection|Timeslot
     */
    public function findForGroup(TeacherGroup $group) : Collection {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\Timeslot s WHERE s.group = :teacherGroup ORDER BY s.start ASC");
        $query->setParameter('teacherGroup', $group);

        return new ArrayCollection($query->getResult());
    }

    /**
     * remove all entries in table and reset id generation
     * @throws \Doctrine\DBAL\Exception
     */
    public function truncate() {
        //based on https://blog.nevercodealone.de/symfony-doctrine-truncate-table/
        $connection = parent::getEntityManager()->getConnection();
        $platform   = $connection->getDatabasePlatform();

        $connection->executeStatement($platform->getTruncateTableSQL('espt_timeslot', true));
    }

}