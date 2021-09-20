<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\CreationSettingsRepository;
use DigiHelfer\EspTBundle\Entity\EventType;
use DigiHelfer\EspTBundle\Entity\EventState;
use DigiHelfer\EspTBundle\Entity\TeacherGroupRepository;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Entity\TimeslotRepository;
use DigiHelfer\EspTBundle\Helpers\DateUtils;
use DigiHelfer\EspTBundle\Security\Privilege;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use IServ\CoreBundle\Controller\AbstractPageController;
use Knp\Menu\FactoryInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @package DigiHelfer\EspTBundle\Controller
 * @Route("/espt")
 */
final class MainController extends AbstractPageController {

    /**
     * @param CreationSettingsRepository $creationSettingsRepository
     * @param TimeslotRepository $timeslotRepository
     * @param TeacherGroupRepository $groupRepository
     * @return Response
     * @throws NonUniqueResultException
     * @Route("/", name="espt_index")
     */
    public function index(CreationSettingsRepository $creationSettingsRepository, TimeslotRepository $timeslotRepository, TeacherGroupRepository $groupRepository): Response {
        $this->addBreadcrumb(_("EspT"));

        $settings = $creationSettingsRepository->findFirst();
        $state = EventState::getState($settings);

        //render different pages depending on user role and the state of the event
        if ($state == EventState::NONE) {
            return $this->render("@DH_EspT/User/None.twig");
        }

        if ($state == EventState::INVITE) {
            if ($this->isGranted(Privilege::TEACHER)) {
                return $this->render("@DH_EspT/User/TeacherInvite.twig", [
                    "startTime" => $settings->getStart(),
                    "endTime" => $settings->getEnd(),
                    "regStart" => $settings->getRegStart(),
                    "timeslots" => $timeslotRepository->findForTeacher($this->authenticatedUser()),
                    ]);
            }

            if ($this->isGranted("ROLE_STUDENT") || $this->isGranted("ROLE_PARENT")) {
                return $this->render("@DH_EspT/User/UserInvite.twig", [
                    "startTime" => $settings->getStart(),
                    "endTime" => $settings->getEnd(),
                    "regStart" => $settings->getRegStart(),
                    "regEnd" => $settings->getRegEnd()]);
            }
        }

        if ($state == EventState::REGISTRATION) {
            if ($this->isGranted(Privilege::TEACHER)) {
                return $this->render("@DH_EspT/User/TeacherRegister.twig", [
                    "regEnd" => $settings->getRegEnd(),
                    "startTime" => $settings->getStart(),
                    "endTime" => $settings->getEnd(),
                    ]);
            }

            if ($this->isGranted("ROLE_STUDENT") || $this->isGranted("ROLE_PARENT")) {
                return $this->render("@DH_EspT/User/UserRegister.twig", [
                    "timeslots" => $timeslotRepository->findAll(),
                    "regStart" => $settings->getRegStart(),
                    "regEnd" => $settings->getRegEnd(),
                    "startTime" => $settings->getStart(),
                    "endTime" => $settings->getEnd(),
                    ]);
            }
        }

        if ($state == EventState::PRINT) {
            if($this->isGranted(Privilege::TEACHER)) {
                $groups = $groupRepository->findFor($this->authenticatedUser());
                return $this->render("@DH_EspT/User/Print.twig", [
                    "groups" => $groups->toArray(),
                ]);
            }
            return $this->render("@DH_EspT/User/Print.twig");

        }

        return $this->render("@DH_EspT/User/None.twig");
    }

    /**
     * @param CreationSettingsRepository $settingsRepository
     * @param TimeslotRepository $timeslotRepository
     * @return Response
     * @Route("/timeslots", name="espt_timeslots", options={"expose": true})
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
                $timeslots = $timeslotRepository->findAll();

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
     * @Route("timeslots/user", name="espt_timeslots_user", options={"expose": true})
     */
    public function timeslotsForUser(TimeslotRepository $timeslotRepository) : Response {
        if($this->isGranted(Privilege::TEACHER)) {
            return $this->json($timeslotRepository->findForTeacher($this->authenticatedUser()));
        }
        return $this->json($timeslotRepository->findForUser($this->authenticatedUser()));
    }

    /**
     * @param Request $request
     * @param TimeslotRepository $timeslotRepository
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws NonUniqueResultException
     * @Route("/timeslots/reserve", name="espt_timeslots_reserve", options={"expose": true}, methods={"POST"})
     */
    public function reserveTimeslot(Request $request, TimeslotRepository $timeslotRepository, EntityManagerInterface $entityManager): Response {
        $id = $request->request->get('id');
        if($id == null) return $this->json(array('success' => false, 'error'=> 'no id'));

        if (!$this->isGranted("ROLE_STUDENT") && !$this->isGranted("ROLE_PARENT")) {
            return $this->json(array('success' => false, 'error' => 'no perms'));
        }

        $timeslot = $timeslotRepository->find($id);
        if ($timeslot == null) {
            return $this->json(array('success' => false, 'error' => 'no timeslot'));
        }

        if ($timeslot->getType()->getName() != EventType::BOOK) return $this->json(array('success' => false, 'error' => 'wrong type', 'type' => $timeslot->getType()));

        if ($timeslot->getUser() != null) {
            if ($timeslot->getUser() === $this->authenticatedUser()) {
                //remove booking for user
                $timeslot->setUser(null);
                $entityManager->persist($timeslot);
                $entityManager->flush();
                return $this->json(array('success' => true));
            }

            return $this->json(array('success' => false, 'error' => 'already booked'));
        }

        $timeslot->setUser($this->authenticatedUser());
        $entityManager->persist($timeslot);
        $entityManager->flush();

        return $this->json(array('success' => true));
    }

    /**
     * {@inheritDoc}
     */
    public static function getSubscribedServices(): array {
        // Take all subscribed services from the parent classes.
        $services = parent::getSubscribedServices();
        // Add services we commonly use and don't want to inject in each controller or action.
        $services[] = FactoryInterface::class;
        return $services;
    }
}