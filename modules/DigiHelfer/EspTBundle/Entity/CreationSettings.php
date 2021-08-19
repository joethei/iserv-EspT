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
     * @ORM\Column(name="start_date", type="datetime_immutable")
     * @var \DateTimeImmutable
     * @Assert\NotBlank()
     */
    private $start;

    /**
     * @ORM\Column(name="end_date", type="datetime_immutable")
     * @var \DateTimeImmutable
     * @Assert\NotBlank()
     */
    private $end;

    /**
     * @ORM\Column(name="registration_start", type="datetime_immutable")
     * @var \DateTimeImmutable
     * @Assert\NotBlank()
     */
    private $regStart;

    /**
     * @ORM\Column(name="registration_end", type="datetime_immutable")
     * @var \DateTimeImmutable
     * @Assert\NotBlank()
     */
    private $regEnd;

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
     * @return \DateTimeImmutable
     */
    public function getRegStart(): \DateTimeImmutable {
        return $this->regStart;
    }

    /**
     * @param \DateTimeImmutable $regStart
     */
    public function setRegStart(\DateTimeImmutable $regStart): void {
        $this->regStart = $regStart;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getRegEnd(): \DateTimeImmutable {
        return $this->regEnd;
    }

    /**
     * @param \DateTimeImmutable $regEnd
     */
    public function setRegEnd(\DateTimeImmutable $regEnd): void {
        $this->regEnd = $regEnd;
    }

}