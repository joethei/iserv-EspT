<?php

namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use IServ\CrudBundle\Entity\CrudInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class CreationSettings
 * @package DigiHelfer\EspTBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="espt_settings")
 * @ORM\HasLifecycleCallbacks
 */
class CreationSettings implements CrudInterface{

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(name="date", type="datetime")
     * @var \DateTime
     * @Assert\NotBlank()
     */
    private $date;

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

    /**
     * @ORM\Column(name="normal_length", type="integer")
     * @var \DateInterval
     * @Assert\Positive()
     */
    private $normalLength;

    /**
     * @ORM\Column(name="invite_length", type="integer")
     * @var \DateInterval
     * @Assert\Positive()
     */
    private $inviteLength;

    /**
     * @ORM\Column(name="max_number_of_invites", type="integer")
     * @var integer
     * @Assert\Positive()
     */
    private $maxNumberOfInvites;

    public function __toString() {
        return "";
    }

    public function getId() : ?int {
        return $this->id;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime {
        return $this->date;
    }

    /**
     * @param \DateTime $date
     */
    public function setDate(\DateTime $date): void {
        $this->date = $date;
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

    /**
     * @return \DateInterval
     */
    public function getNormalLength(): \DateInterval {
        return $this->normalLength;
    }

    /**
     * @param \DateInterval $normalLength
     */
    public function setNormalLength(\DateInterval $normalLength): void {
        $this->normalLength = $normalLength;
    }

    /**
     * @return \DateInterval
     */
    public function getInviteLength(): \DateInterval {
        return $this->inviteLength;
    }

    /**
     * @param \DateInterval $inviteLength
     */
    public function setInviteLength(\DateInterval $inviteLength): void {
        $this->inviteLength = $inviteLength;
    }

    /**
     * @return int
     */
    public function getMaxNumberOfInvites(): int {
        return $this->maxNumberOfInvites;
    }

    /**
     * @param int $maxNumberOfInvites
     */
    public function setMaxNumberOfInvites(int $maxNumberOfInvites): void {
        $this->maxNumberOfInvites = $maxNumberOfInvites;
    }

}