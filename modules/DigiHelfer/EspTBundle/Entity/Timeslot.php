<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinColumn;
use IServ\CoreBundle\Entity\User;
use IServ\CrudBundle\Entity\CrudInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Timeslot
 * @package DigiHelfer\EspTBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="espt_timeslot")
 * @ORM\HasLifecycleCallbacks
 */
class Timeslot implements CrudInterface {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @ORM\Column(type="time_immutable", name="start_time")
     * @var \DateTimeImmutable
     * @Assert\NotBlank()
     */
    private $start;

    /**
     * @ORM\Column(type="time_immutable", name="end_time")
     * @var \DateTimeImmutable
     * @Assert\NotBlank()
     */
    private $end;

    /**
     * @ORM\Column(type="espt_eventType")
     * @var EventType
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="DigiHelfer\EspTBundle\Entity\TeacherGroup", inversedBy="timeslots")
     * @JoinColumn(name="group_id" referencedColumnName="id")
     * @var TeacherGroup
     */
    private $group;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="\IServ\CoreBundle\Entity\User", fetch="EAGER")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=true)
     */
    private $user;

    public function __toString() {
        return "";
    }

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

    /**
     * @return \DateTimeImmutable
     */
    public function getStart(): \DateTimeImmutable {
        return $this->start;
    }

    /**
     * @param \DateTimeImmutable $start
     */
    public function setStart(\DateTimeImmutable $start): void {
        $this->start = $start;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getEnd(): \DateTimeImmutable {
        return $this->end;
    }

    /**
     * @param \DateTimeImmutable $end
     */
    public function setEnd(\DateTimeImmutable $end): void {
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