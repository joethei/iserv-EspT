<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Helpers;

use DateTimeImmutable;
use DigiHelfer\EspTBundle\Entity\CreationSettings;
use DigiHelfer\EspTBundle\Entity\EventType;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use Doctrine\Common\Collections\Collection;
use IServ\CoreBundle\Entity\User;

class DateUtils {

    /**
     * adapted from https://stackoverflow.com/a/14203947/5589264
     * @param DateTimeImmutable $start_one
     * @param DateTimeImmutable $end_one
     * @param DateTimeImmutable $start_two
     * @param DateTimeImmutable $end_two
     * @return int overlap in minutes
     */
    public static function datesOverlap(DateTimeImmutable $start_one, DateTimeImmutable $end_one, DateTimeImmutable $start_two, DateTimeImmutable $end_two): int {
        if ($start_one <= $end_two && $end_one >= $start_two) { //If the time overlaps
            return min($end_one, $end_two)->diff(max($start_two, $start_one))->i; //return how many minutes overlap
        }
        return 0; //no overlap
    }

    //TODO: move this to a better place
    /**
     * reformat data for consumption by vue-schedule-view component.
     * @param CreationSettings $settings
     * @param User $user
     * @param Collection|Timeslot $timeslots
     * @return array
     */
    public static function buildTimeslotArray(CreationSettings $settings, User $user, Collection $timeslots): array {
        $schedules = array();
        /**@var Timeslot $timeslot*/
        foreach ($timeslots as $timeslot) {
            $data_timeslot = array();

            $startDate = $settings->getStart();
            $startYear = (int)$startDate->format("Y");
            $startMonth = (int)$startDate->format("m");
            $startDay = (int)$startDate->format("d");

            $data_timeslot['start'] = $timeslot->getStart()->setDate($startYear, $startMonth, $startDay);
            $data_timeslot['end'] = $timeslot->getEnd()->setDate($startYear, $startMonth, $startDay);
            $data_timeslot['id'] = $timeslot->getId();

            $color = 'red';
            $name = '';
            switch ($timeslot->getType()->getName()) {
                case EventType::BOOK:
                case EventType::INVITE :
                    $color = 'green';
                    $name = _('espt_timeslot_type_free');
                    break;
                case EventType::BREAK :
                    $color = 'gray';
                    $name = _('espt_timeslot_type_break');
                    break;
            }

            if ($timeslot->getUser() != null) {
                $name = _('espt_timeslot_type_blocked');
                $color = 'red';
                if ($timeslot->getUser() === $user) {
                    $name = _('espt_timeslot_type_booked');
                    $color = 'yellow';
                }
            }

            $data_timeslot['color'] = $color;
            $data_timeslot['name'] = $name;

            $id = $timeslot->getGroup()->getId();

            $groupExists = false;

            for ($i = 0; $i < sizeof($schedules); ++$i) {
                if($id === $schedules[$i]['id']) {
                    $schedules[$i]['events'][] = $data_timeslot;
                    $groupExists = true;
                }
            }
            if(!$groupExists) {
                $data_event = array('events' => array($data_timeslot));
                $data_event['id'] = $timeslot->getGroup()->getId();

                $usernames = implode(' & ', $timeslot->getGroup()->getUsers()->toArray());

                $data_event['title'] = $usernames;
                $data_event['subtitle'] = _('Room') . " " . $timeslot->getGroup()->getRoom();

                $schedules[] = $data_event;
            }

            //events need to be sorted for correct display in ScheduleView
            for ($i = 0; $i < sizeof($schedules); ++$i) {
                usort($schedules[$i]['events'], function ($a, $b) {
                    return $a['start'] <=> $b['start'];
                });
            }


        }
        $result = array();



        $result['schedules'] = $schedules;

        //TODO: don't hardcode scaleFactor
        $settings = array('start' => $settings->getStart(), 'end' => $settings->getEnd(), 'scaleFactor' => 2);
        $result['settings'] = $settings;

        return $result;
    }

}