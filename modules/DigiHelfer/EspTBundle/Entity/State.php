<?php

namespace DigiHelfer\EspTBundle\Entity;

use DateTime;

class State {
    const NONE = 'none';
    const INVITE = 'invite';
    const REGISTRATION = 'registration';
    const PRINT = 'print';

    public static function getState(?CreationSettings $settings) : string {
        $now = new DateTime('now');

        if($settings == null || $settings->getEnd() > $now)
            return State::NONE;
        if ($settings->getRegStart() > $now)
            return State::INVITE;
        if ($settings->getRegStart() < $now && $settings->getRegEnd() > $now)
            return State::REGISTRATION;
        if($settings->getRegEnd() < $now)
            return State::PRINT;

        return State::NONE;
    }
}