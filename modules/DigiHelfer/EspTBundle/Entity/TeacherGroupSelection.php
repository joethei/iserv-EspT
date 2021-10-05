<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use IServ\CoreBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="espt_teacher_group_selection")
 */
class TeacherGroupSelection {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="IServ\CoreBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="act", nullable=true)
     */
    private $user;

    /**
     * @var TeacherGroup[]
     * @ORM\ManyToMany(targetEntity="DigiHelfer\EspTBundle\Entity\TeacherGroup")
     * @JoinTable(name="espt_teacher_group_selections",
     *    joinColumns={@JoinColumn(name="selection_id", referencedColumnName="id")},
     *    inverseJoinColumns={@JoinColumn(name="group_id", referencedColumnName="id")}
     * )
     */
    private $groups;

    /**
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void {
        $this->id = $id;
    }

    public function __construct() {
        $this->groups = new ArrayCollection();
    }

    /**
     * @return User
     */
    public function getUser(): User {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user): void {
        $this->user = $user;
    }

    /**
     * @return ArrayCollection
     */
    public function getGroups() : ArrayCollection {
        return $this->groups;
    }

    /**
     * @param ArrayCollection $groups
     */
    public function setGroups(ArrayCollection $groups): void {
        $this->groups = $groups;
    }

}