<?php

namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CreationSettings
 * @package DigiHelfer\EspTBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="espt_settings")
 * @ORM\HasLifecycleCallbacks
 */
class CreationSettings {

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="start_date", type="datetime")
     * @var \DateTime
     * @Assert\NotBlank()
     */
    private $start;

    /**
     * @ORM\Column(name="end_date", type="datetime")
     * @var \DateTime
     * @Assert\NotBlank()
     */
    private $end;

    /**
     * @ORM\Column(name="registration_start", type="datetime")
     * @var \DateTime
     * @Assert\NotBlank()
     */
    private $regStart;

    /**
     * @ORM\Column(name="registration_end", type="datetime")
     * @var \DateTime
     * @Assert\NotBlank()
     */
    private $regEnd;

    public function __toString() {
        return "";
    }

    public function getId() : ?int {
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
     * @return \DateTime
     */
    public function getRegStart(): \DateTime {
        return $this->regStart;
    }

    /**
     * @param \DateTime $regStart
     */
    public function setRegStart(\DateTime $regStart): void {
        $this->regStart = $regStart;
    }

    /**
     * @return \DateTime
     */
    public function getRegEnd(): \DateTime {
        return $this->regEnd;
    }

    /**
     * @param \DateTime $regEnd
     */
    public function setRegEnd(\DateTime $regEnd): void {
        $this->regEnd = $regEnd;
    }

    public function addTimeslot(Timeslot $timeslot) : void {

    }
    

}