<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Tests;


use DigiHelfer\EspTBundle\Entity\CreationSettings;
use DigiHelfer\EspTBundle\Entity\EventType;
use DigiHelfer\EspTBundle\Entity\TeacherGroup;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Helpers\DateUtils;
use Doctrine\Common\Collections\ArrayCollection;
use IServ\CoreBundle\Entity\User;
use PHPUnit\Framework\TestCase;

class TimeslotDisplayArrayTest extends TestCase {


    public function testData() : void {
        $this->markTestSkipped("implementation not correct yet");
        $user1 = new User();
        $user1->setName("Test User");

        $teacher1 = new User();
        $teacher1->setName("Test Teacher");

        $teacher2 = new User();
        $teacher2->setName("Test Teacher 2");

        $settings = new CreationSettings();
        $settings->setStart(new \DateTimeImmutable('2021-06-06'));
        $settings->setEnd(new \DateTimeImmutable('2021-06-07'));
        $settings->setRegStart(new \DateTimeImmutable('2021-06-06'));
        $settings->setRegEnd(new \DateTimeImmutable('2021-06-07'));

        $break = new EventType();
        $break->setName(EventType::BREAK);

        $booking = new EventType();
        $booking->setName(EventType::BOOK);

        $invite = new EventType();
        $invite->setName(EventType::INVITE);

        $blocked = new EventType();
        $blocked->setName(EventType::BLOCKED);

        $group1 = new TeacherGroup();
        $group1->setRoom("Test");
        $teachers1 = new ArrayCollection();
        $teachers1->add($teacher1);
        $teachers1->add($teacher2);
        $group1->setUsers($teachers1);

        $timeslot1 = new Timeslot();
        $timeslot1->setStart(new \DateTimeImmutable('2021-06-06'));
        $timeslot1->setEnd(new \DateTimeImmutable('2021-06-06'));
        $timeslot1->setType($invite);
        $timeslot1->setGroup($group1);

        $timeslots = new ArrayCollection();
        $timeslots->add($timeslot1);

        $test = array();

        $this->assertEquals($test, DateUtils::buildTimeslotArray($settings, $user1, $timeslots));
    }
}