<?php


namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use IServ\CoreBundle\Entity\User;

/**
 * Class TeacherGroup
 * @package DigiHelfer\EspTBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="espt_teacher_group")
 */
class TeacherGroup {

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
     * @ORM\OneToMany(targetEntity="\IServ\CoreBundle\Entity\User", fetch="EAGER")
     * @var Collection|User
     * @JoinTable(name="espt_teacher_groups",
     *      joinColumns={@JoinColumn(name="user_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="group_id", referencedColumnName="id")}
     *      )
     */
    private $users;

    /**
     * @var TimeslotTemplateCollection
     * @ORM\ManyToOne(targetEntity="DigiHelfer\EspTBundle\Entity\TimeslotTemplateCollection")
     */
    private $timeslotTemplate;

    public function __construct() {
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


    public function __toString() : string {
        return (string)$this->getId();
    }
}