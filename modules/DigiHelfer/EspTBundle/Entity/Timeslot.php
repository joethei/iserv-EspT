<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use IServ\CoreBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Timeslot
 * @package DigiHelfer\EspTBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="espt_timeslot")
 * @ORM\HasLifecycleCallbacks
 */
class Timeslot {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="time")
     * @var \DateTime
     * @Assert\NotBlank()
     */
    private $start;

    /**
     * @ORM\Column(type="time")
     * @var \DateTime
     * @Assert\NotBlank()
     */
    private $end;

    /**
     * @ORM\Column(type="integer")
     * @var EventType
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ORM\OneToMany(targetEntity="TeacherGroup", mappedBy="group", fetch="EAGER")
     * @var TeacherGroup
     */
    private $group;

    /**
     * @var User
     * @ORM\OneToMany(targetEntity="\IServ\CoreBundle\Entity\User", mappedBy="user", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    public function __toString() {
        return "";
    }

    public function getId(): int {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getStart(): \DateTime {
        return $this->start;
    }

    /**
     * @param \DateTime $start
     */
    public function setStart(\DateTime $start): void {
        $this->start = $start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd(): \DateTime {
        return $this->end;
    }

    /**
     * @param \DateTime $end
     */
    public function setEnd(\DateTime $end): void {
        $this->end = $end;
    }

    /**
     * @return EventType
     */
    public function getType(): EventType {
        return $this->type;
    }

    /**
     * @param EventType $type
     */
    public function setType(EventType $type): void {
        $this->type = $type;
    }

    /**
     * @return TeacherGroup
     */
    public function getGroup(): TeacherGroup {
        return $this->group;
    }

    /**
     * @param TeacherGroup $group
     */
    public function setGroup(TeacherGroup $group): void {
        $this->group = $group;
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
}