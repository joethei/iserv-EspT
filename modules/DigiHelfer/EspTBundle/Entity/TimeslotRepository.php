<?php

namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use IServ\CoreBundle\Entity\User;

class TimeslotRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, Timeslot::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function find($id, $lockMode = null, $lockVersion = null) : Timeslot {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\Timeslot s WHERE s.id = :id");
        $query->setParameter('id', $id);

        return $query->getOneOrNullResult();
    }

    public function findAll() {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\Timeslot s");

        return $query->getArrayResult();
    }

    /**
     * @param User $user
     * @return Timeslot[]
     */
    public function findForTeacher(User $user) : array {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT t FROM DigiHelfer\EspTBundle\Entity\Timeslot s JOIN s.group g WHERE :user MEMBER OF g.users");
        $query->setParameter('user', $user);

        return $query->getArrayResult();
    }

    /**
     * @param User $user
     * @return Timeslot[]
     */
    public function findForUser(User $user) : array {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\Timeslot s WHERE s.user = :user");
        $query->setParameter('user', $user);

        return $query->getArrayResult();
    }

    public function findForGroup(TeacherGroup $group) {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\Timeslot s WHERE group = :teacherGroup");
        $query->setParameter('teacherGroup', $group);

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