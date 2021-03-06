<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\JoinColumn;
use IServ\CrudBundle\Entity\CrudInterface;

/**
 * Class TimeslotTemplateCollection
 * @package DigiHelfer\EspTBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="espt_timeslot_template_collection")
 * @ORM\HasLifecycleCallbacks
 */
class TimeslotTemplateCollection implements CrudInterface {

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
    private $name;

    /**
     * @ORM\Column(type="integer")
     * @var int
     */
    private $day = 1;

    /**
     *
     * @var Collection|TimeslotTemplate[]
     * @ORM\OneToMany(targetEntity="DigiHelfer\EspTBundle\Entity\TimeslotTemplate", mappedBy="collection", cascade={"all"}, orphanRemoval=true)
     */
    private $timeslots;

    /**
     * @var Collection|TeacherGroup[]
     *  @ORM\ManyToMany(targetEntity="DigiHelfer\EspTBundle\Entity\TeacherGroup")
     *  @JoinTable(name="espt_timeslot_templates",
     *    joinColumns={@JoinColumn(name="template_id", referencedColumnName="id")},
     *    inverseJoinColumns={@JoinColumn(name="group_id", referencedColumnName="id")}
     *  )
     */
    private $groups;

    public function __construct() {
        $this->timeslots = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }

    public function __toString() : string {
        return $this->name;
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
    public function getName(): ?string {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getDay(): int {
        return $this->day;
    }

    /**
     * @param int $day
     */
    public function setDay(int $day): void {
        $this->day = $day;
    }

    /**
     * @return Collection|TimeslotTemplate[]
     */
    public function getTimeslots(): Collection {
        return $this->timeslots;
    }

    /**
     * @param ArrayCollection $timeslots
     */
    public function setTimeslots(ArrayCollection $timeslots): void {
        $this->timeslots = $timeslots;
    }

    public function addTimeslot(TimeslotTemplate $timeslot) : void {
        if(!$this->timeslots->contains($timeslot)) {
            $this->timeslots->add($timeslot);
            $timeslot->setCollection($this);
        }
    }

    public function removeTimeslot(TimeslotTemplate $timeslot) : void {
        $this->timeslots->removeElement($timeslot);
    }

    /**
     * @return Collection|TeacherGroup[]
     */
    public function getGroups(): Collection {
        return $this->groups;
    }

    /**
     * @param Collection|TeacherGroup[] $groups
     */
    public function setGroups(Collection $groups): void {
        $this->groups = $groups;
    }
}