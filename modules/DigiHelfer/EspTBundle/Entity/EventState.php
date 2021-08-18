<?php

namespace DigiHelfer\EspTBundle\Entity;

use DateTime;

class EventState {
    const NONE = 'none';
    const INVITE = 'invite';
    const REGISTRATION = 'registration';
    const PRINT = 'print';

    public static function getState(?CreationSettings $settings) : string {
        $now = new DateTime('now');

        if($settings == null || $settings->getEnd() > $now)
            return EventState::NONE;
        if ($settings->getRegStart() > $now)
            return EventState::INVITE;
        if ($settings->getRegStart() < $now && $settings->getRegEnd() > $now)
            return EventState::REGISTRATION;
        if($settings->getRegEnd() < $now)
            return EventState::PRINT;

        return EventState::NONE;
    }
}