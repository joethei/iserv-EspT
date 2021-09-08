<?php

namespace DigiHelfer\EspTBundle\Entity;

use DateTime;

class EventState {
    /**
     * No dates set
     */
    const NONE = 'none';

    /**
     * teachers can invite students, students cannot book
     */
    const INVITE = 'invite';

    /**
     * students can book events with teachers, teachers cannot invite students
     */
    const REGISTRATION = 'registration';

    /**
     * only admins can change timeslots.
     * Students, teachers and admins can view & print a listing of all their timeslots.
     * Admins can print a room sheet.
     */
    const PRINT = 'print';

    /**
     * get current state of the event depending on current time and times defined in settings.
     * @param CreationSettings|null $settings settings to base the calculations on.
     * @return string current state, valid states are defined as const's on this class
     */
    public static function getState(?CreationSettings $settings) : string {
        $now = new DateTime('now');

        if($settings == null || $settings->getEnd() < $now)
            return EventState::NONE;
        if ($now < $settings->getRegStart())
            return EventState::INVITE;
        if ($settings->getRegStart() < $now && $now < $settings->getRegEnd())
            return EventState::REGISTRATION;
        if($settings->getRegEnd() < $now)
            return EventState::PRINT;

        return EventState::NONE;
    }
}