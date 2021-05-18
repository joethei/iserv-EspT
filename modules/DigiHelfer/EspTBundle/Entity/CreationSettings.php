<?php

namespace DigiHelfer\EspTBundle\Entity;

use DateTime;
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
     * @ORM\Column(name="start_date", type="datetime")
     * @var DateTime
     * @Assert\NotBlank()
     */
    private $startDate;

    /**
     * @ORM\Column(name="end_date", type="datetime")
     * @var DateTime
     * @Assert\NotBlank()
     */
    private $endDate;

    /**
     * @ORM\Column(name="registration_start", type="datetime")
     * @var DateTime
     * @Assert\NotBlank()
     */
    private $registrationStart;

    /**
     * @ORM\Column(name="registration_end", type="datetime")
     * @var DateTime
     * @Assert\NotBlank()
     */
    private $registrationEnd;

    /**
     * @ORM\Column(name="normal_length", type="integer")
     * @var int
     * @Assert\Positive()
     */
    private $normalLength;

    /**
     * @ORM\Column(name="invite_length", type="integer")
     * @var integer
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
}