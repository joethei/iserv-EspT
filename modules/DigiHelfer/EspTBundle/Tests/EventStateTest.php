<?php

namespace DigiHelfer\EspTBundle\Tests;

use DigiHelfer\EspTBundle\Entity\CreationSettings;
use DigiHelfer\EspTBundle\Entity\EventState;
use PHPUnit\Framework\TestCase;

class EventStateTest extends TestCase {

    public function testStateNone() : void {
        $now = new \DateTime('now');
        $settings = null;

        $state = EventState::getState($settings);
        $this->assertEquals(EventState::NONE, $state);

        $settings = new CreationSettings();
        $settings->setEnd($now->sub(new \DateInterval('1D')));

        $state = EventState::getState($settings);
        $this->assertEquals(EventState::NONE, $state);
    }

    public function testStateInvite() : void {
        $now = new \DateTime('now');
        $settings = new CreationSettings();

        $settings->setStart($now->add(new \DateInterval('5D')));
        $settings->setEnd($now->add(new \DateInterval('6D')));
        $settings->setRegStart($now->add(new \DateInterval('1D')));
        $settings->setRegEnd($now->add(new \DateInterval('2D')));

        $state = EventState::getState($settings);
        $this->assertEquals(EventState::INVITE, $state);
    }

    public function testStateRegistration() : void {
        $now = new \DateTime('now');
        $settings = new CreationSettings();

        $settings->setStart($now->add(new \DateInterval('4D')));
        $settings->setEnd($now->add(new \DateInterval('6D')));
        $settings->setRegStart($now->sub(new \DateInterval('1D')));
        $settings->setRegEnd($now->add(new \DateInterval('1D')));

        $state = EventState::getState($settings);
        $this->assertEquals(EventState::REGISTRATION, $state);
    }

    public function testStatePrint() : void {
        $now = new \DateTime('now');

        $settings = new CreationSettings();
        $settings->setStart($now->sub(new \DateInterval('1H')));
        $settings->setEnd($now->add(new \DateInterval('3H')));
        $settings->setRegStart($now->sub(new \DateInterval('2D')));
        $settings->setRegEnd($now->sub(new \DateInterval('1D')));

        $state = EventState::getState($settings);
        $this->assertEquals(EventState::PRINT, $state);
    }

}