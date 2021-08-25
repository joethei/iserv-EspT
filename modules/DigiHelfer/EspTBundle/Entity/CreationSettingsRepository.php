<?php
declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

final class CreationSettingsRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, CreationSettings::class);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findFirst(): ?CreationSettings {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery("SELECT s FROM DigiHelfer\EspTBundle\Entity\CreationSettings s");

        return $query->setMaxResults(1)->getOneOrNullResult();
    }
}