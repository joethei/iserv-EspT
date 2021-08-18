<?php

namespace DigiHelfer\EspTBundle\Tests;

use DigiHelfer\EspTBundle\Entity\CreationSettings;
use DigiHelfer\EspTBundle\Entity\State;
use PHPUnit\Framework\TestCase;

class StateTest extends TestCase {

    public function testStateNone() : void {
        $settings = null;
        $state = State::getState($settings);
        $this->assertEquals(State::NONE, $state);

        $settings = new CreationSettings();
        $settings->setEnd(new \DateTime('yesterday'));
        $state = State::getState($settings);
        $this->assertEquals(State::NONE, $state);
    }

    public function testStateInvite() : void {
        $settings = new CreationSettings();
        $settings->setStart(new \DateTime('in 5 days'));
        $settings->setEnd(new \DateTime('in 6 days'));
        $settings->setRegStart(new \DateTime('tomorrow'));
        $settings->setRegEnd(new \DateTime('in 2 days'));
        $state = State::getState($settings);
        $this->assertEquals(State::INVITE, $state);
    }

    public function testStateRegistration() : void {
        $settings = new CreationSettings();
        $settings->setStart(new \DateTime('in 3 days'));
        $settings->setEnd(new \DateTime('in 5 days'));
        $settings->setRegStart(new \DateTime('yesterday'));
        $settings->setRegEnd(new \DateTime('tomorrow'));

        $state = State::getState($settings);
        $this->assertEquals(State::REGISTRATION, $state);
    }

    public function testStatePrint() : void {
        $settings = new CreationSettings();
        $settings->setStart(new \DateTime('now'));
        $settings->setEnd(new \DateTime('tomorrow'));
        $settings->setRegStart(new \DateTime('2 days ago'));
        $settings->setRegEnd(new \DateTime('yesterday'));

        $state = State::getState($settings);
        $this->assertEquals(State::PRINT, $state);
    }

}