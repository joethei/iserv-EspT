<?php

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Repository\CreationSettingsRepository;
use DigiHelfer\EspTBundle\Entity\EventState;
use DigiHelfer\EspTBundle\Entity\EventType;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Repository\TimeslotRepository;
use DigiHelfer\EspTBundle\Helpers\DateUtils;
use DigiHelfer\EspTBundle\Security\Privilege;
use Doctrine\ORM\NonUniqueResultException;
use IServ\CoreBundle\Controller\AbstractPageController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Class TimeslotController
 * @package DigiHelfer\EspTBundle\Controller
 * @Route("/espt/timeslots")
 */
class TimeslotController extends AbstractPageController {

    /**
     * @param CreationSettingsRepository $settingsRepository
     * @param TimeslotRepository $timeslotRepository
     * @return Response
     * @Route("/", name="espt_timeslots", options={"expose": true})
     * @throws NonUniqueResultException
     */
    public function timeslots(CreationSettingsRepository $settingsRepository, TimeslotRepository $timeslotRepository): Response {
        $settings = $settingsRepository->findFirst();
        $state = EventState::getState($settings);

        if ($this->isGranted(Privilege::TEACHER)) {
            if ($state == EventState::INVITE) {
                $timeslots = $timeslotRepository->findForTeacher($this->authenticatedUser());

                $timeslots = $timeslots->filter(function ($entry) {
                    /** @var Timeslot $entry */
                    return $entry->getType()->getName() == EventType::INVITE;
                });

                return $this->json(DateUtils::buildTimeslotArray($settings, $this->authenticatedUser(), $timeslots));
            }
        }

        if ($this->isGranted("ROLE_STUDENT") || $this->isGranted("ROLE_PARENT")) {
            if ($state == EventState::REGISTRATION) {
                $timeslots = $timeslotRepository->findForSelection($this->authenticatedUser());

                $timeslots = $timeslots->filter(function ($entry) {
                    /** @var Timeslot $entry */
                    return $entry->getType()->getName() != EventType::INVITE;
                });

                return $this->json(DateUtils::buildTimeslotArray($settings, $this->authenticatedUser(), $timeslots));
            }
        }

        return $this->json([]);
    }

    /**
     * @param TimeslotRepository $timeslotRepository
     * @return Response
     * @Route("/user", name="espt_timeslots_user", options={"expose": true})
     */
    public function timeslotsForUser(TimeslotRepository $timeslotRepository) : Response {
        if($this->isGranted(Privilege::TEACHER)) {
            $timeslots = $timeslotRepository->findForTeacher($this->authenticatedUser());
        }else {
            $timeslots = $timeslotRepository->findForUser($this->authenticatedUser());
        }
        $result = array();

        /** @var Timeslot $timeslot*/
        foreach ($timeslots as $timeslot) {

            //don't show timeslots without bookings
            if($timeslot->getUser() != null) {
                $result[] = array(
                    'start' => $timeslot->getStart()->format("d.m G:i"),
                    'end' => $timeslot->getEnd()->format("d.m G:i"),
                    'type' => $timeslot->getType()->getName(),
                    'user' => $timeslot->getUser()->getNameByFirstname(),
                    'group' => implode(', ', $timeslot->getGroup()->getUsers()->toArray()),
                    'room' => $timeslot->getGroup()->getRoom()
                );
            }
        }

        return $this->json($result);
    }

}