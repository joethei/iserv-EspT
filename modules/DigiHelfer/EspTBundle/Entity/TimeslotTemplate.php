<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use IServ\CrudBundle\Entity\CrudInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TimeslotTemplate
 * @package DigiHelfer\EspTBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="espt_timeslot_template")
 * @ORM\HasLifecycleCallbacks
 */
class TimeslotTemplate implements CrudInterface {

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
     * @ORM\Column(type="integer")
     * @var EventType
     * @Assert\NotBlank()
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity="DigiHelfer\EspTBundle\Entity\TimeslotTemplateCollection", inversedBy="timeslots")
     * @var TimeslotTemplateCollection
     */
    private $collection;

    public function __toString() : string {
        return (string)$this->id;
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
     * @return TimeslotTemplateCollection
     */
    public function getCollection(): TimeslotTemplateCollection {
        return $this->collection;
    }

    /**
     * @param TimeslotTemplateCollection $collection
     */
    public function setCollection(TimeslotTemplateCollection $collection): void {
        $this->collection = $collection;
    }

}