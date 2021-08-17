<?php


namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use IServ\CoreBundle\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class TeacherGroup
 * @package DigiHelfer\EspTBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="teacher_group")
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
     * @var User
     * @ORM\OneToMany(targetEntity="\IServ\CoreBundle\Entity\User", mappedBy="user")
     * @var ArrayCollection
     */
    private $users;

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

    public function __toString() : string {
        return (string)$this->getId();
    }
}