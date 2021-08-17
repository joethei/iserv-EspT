<?php


namespace DigiHelfer\EspTBundle\Entity;


use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Timeslot
 * @package DigiHelfer\EspTBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="espt_event")
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
     * @ORM\Column(type="datetime")
     * @var \DateTime
     * @Assert\NotBlank()
     */
    private $start;

    /**
     * @ORM\Column(type="datetime")
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
     * @ORM\OneToMany(targetEntity="TeacherGroup", mappedBy="group")
     * @var TeacherGroup
     */
    private $group;

    public function __toString() {
        return "";
    }

    public function getId() {
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
     * @return int
     */
    public function getType(): int {
        return $this->type;
    }

    /**
     * @param int $type
     */
    public function setType(int $type): void {
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
}