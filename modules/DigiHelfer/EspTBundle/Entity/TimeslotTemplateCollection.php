<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
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
     *
     * @var Collection|TimeslotTemplate[]
     * @ORM\OneToMany(targetEntity="DigiHelfer\EspTBundle\Entity\TimeslotTemplate", mappedBy="collection", cascade={"persist"})
     */
    private $timeslots;

    /**
     * @var TeacherGroup[]
     * @ORM\OneToMany(targetEntity="DigiHelfer\EspTBundle\Entity\TeacherGroup", mappedBy="timeslotTemplate")
     */
    private $groups;

    public function __construct() {
        $this->timeslots = new ArrayCollection();
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
     * @return ArrayCollection
     */
    public function getTimeslots(): ArrayCollection {
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
        }
    }

    public function removeTimeslot(TimeslotTemplate $timeslot) : void {
        $this->timeslots->remove($timeslot);
    }

    /**
     * @return TeacherGroup[]
     */
    public function getGroups(): array {
        return $this->groups;
    }

    /**
     * @param TeacherGroup[] $groups
     */
    public function setGroups(array $groups): void {
        $this->groups = $groups;
    }
}