<?php


namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use IServ\CoreBundle\Entity\User;
use IServ\CrudBundle\Entity\CrudInterface;

/**
 * Class TeacherGroup
 * @package DigiHelfer\EspTBundle\Entity
 * @ORM\Entity(repositoryClass="DigiHelfer\EspTBundle\Repository\TeacherGroupRepository")
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
     * @ORM\Column(type="string")
     * @var string|null
     */
    private $room;

    /**
     * @ORM\ManyToMany(targetEntity="\IServ\CoreBundle\Entity\User")
     * @JoinTable(name="espt_teacher_groups",
     *    joinColumns={@JoinColumn(name="group_id", referencedColumnName="id")},
     *    inverseJoinColumns={@JoinColumn(name="user_id", referencedColumnName="act")}
     *  )
     * @var User[]
     */
    private $users;

    /**
     * @var Collection|TimeslotTemplateCollection[]|null
     * @ORM\ManyToMany(targetEntity="DigiHelfer\EspTBundle\Entity\TimeslotTemplateCollection")
     *  @JoinTable(name="espt_timeslot_templates",
     *    joinColumns={@JoinColumn(name="group_id", referencedColumnName="id")},
     *    inverseJoinColumns={@JoinColumn(name="template_id", referencedColumnName="id")}
     *  )
     */
    private $timeslotTemplates;

    /**
     * @var Timeslot[]
     * @ORM\OneToMany(targetEntity="DigiHelfer\EspTBundle\Entity\Timeslot", mappedBy="group", cascade={"all"})
     */
    private $timeslots;

    public function __construct() {
        $this->timeslots = new ArrayCollection();
        $this->users = new ArrayCollection();
    }

    public function __toString() : string {
        return implode(', ', $this->getUsers()->toArray());
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
    public function getUsers(): Collection {
        return $this->users;
    }

    /**
     * @param ArrayCollection $users
     */
    public function setUsers(ArrayCollection $users): void {
        $this->users = $users;
    }

    /**
     * @return Collection|TimeslotTemplateCollection[]|null
     */
    public function getTimeslotTemplates() {
        return $this->timeslotTemplates;
    }

    /**
     * @param TimeslotTemplateCollection|Collection|null $timeslotTemplates
     */
    public function setTimeslotTemplates($timeslotTemplates): void {
        $this->timeslotTemplates = $timeslotTemplates;
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
}