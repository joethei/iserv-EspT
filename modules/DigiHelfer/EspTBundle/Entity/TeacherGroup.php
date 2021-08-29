<?php


namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use IServ\CoreBundle\Entity\User;
use IServ\CrudBundle\Entity\CrudInterface;

/**
 * Class TeacherGroup
 * @package DigiHelfer\EspTBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="espt_teacher_group")
 */
class TeacherGroup implements CrudInterface {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var integer
     */
    private $id;

    /**
     * @var string|null
     */
    private $room;

    /**
     * @ORM\ManyToMany(targetEntity="\IServ\CoreBundle\Entity\User")
     *  @JoinTable(name="espt_teacher_groups",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="group_id", referencedColumnName="id")}
     *      )
     * @var Collection|User
     */
    private $users;

    /**
     * @var TimeslotTemplateCollection
     * @ORM\ManyToOne(targetEntity="DigiHelfer\EspTBundle\Entity\TimeslotTemplateCollection")
     */
    private $timeslotTemplate;

    /**
     * @var Timeslot[]
     * @ORM\OneToMany(targetEntity="DigiHelfer\EspTBundle\Entity\Timeslot" mappedBy="timeslot")
     */
    private $timeslots;

    public function __construct() {
        $this->timeslots = new ArrayCollection();
        $this->users = new ArrayCollection();
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
     * @return string
     */
    public function getRoom(): ?string {
        return $this->room;
    }

    /**
     * @param string $room
     */
    public function setRoom(string $room): void {
        $this->room = $room;
    }

    /**
     * @return ArrayCollection
     */
    public function getUsers(): ArrayCollection {
        return $this->users;
    }

    /**
     * @param ArrayCollection $users
     */
    public function setUsers(ArrayCollection $users): void {
        $this->users = $users;
    }

    /**
     * @return TimeslotTemplateCollection
     */
    public function getTimeslotTemplate(): TimeslotTemplateCollection {
        return $this->timeslotTemplate;
    }

    /**
     * @param TimeslotTemplateCollection $timeslotTemplate
     */
    public function setTimeslotTemplate(TimeslotTemplateCollection $timeslotTemplate): void {
        $this->timeslotTemplate = $timeslotTemplate;
    }

    /**
     * @return Timeslot[]
     */
    public function getTimeslots() {
        return $this->timeslots;
    }

    /**
     * @param Timeslot[] $timeslots
     */
    public function setTimeslots(array $timeslots): void {
        $this->timeslots = $timeslots;
    }


    public function __toString() : string {
        return (string)$this->getId();
    }
}