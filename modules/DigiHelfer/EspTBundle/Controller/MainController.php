<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\CreationSettings;
use DigiHelfer\EspTBundle\Entity\CreationSettingsRepository;
use DigiHelfer\EspTBundle\Entity\EventType;
use DigiHelfer\EspTBundle\Entity\EventState;
use DigiHelfer\EspTBundle\Entity\TeacherGroupRepository;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Entity\TimeslotRepository;
use Doctrine\ORM\EntityManager;
use IServ\CoreBundle\Controller\AbstractPageController;
use IServ\CoreBundle\Entity\User;
use Knp\Menu\FactoryInterface;
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
     * @param TeacherGroupRepository $groupRepository
     * @param TimeslotRepository $timeslotRepository
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @Route("/", name="espt_index")
     */
    public function index(CreationSettingsRepository $creationSettingsRepository, TeacherGroupRepository $groupRepository, TimeslotRepository $timeslotRepository): Response {
        $this->addBreadcrumb(_("EspT"));

        $settings = $creationSettingsRepository->findFirst();
        $state = EventState::getState($settings);

        if ($state == EventState::NONE) {
            return $this->render("@DH_EspT/User/None.twig");
        }

        if ($state == EventState::INVITE) {
            if ($this->isGranted("ROLE_TEACHER")) {
                return $this->render("@DH_EspT/User/TeacherInvite.twig", ["startTime" => $settings->getStart(), "endTime" => $settings->getEnd(), "regStart" => $settings->getRegStart(), "timeslots" => $timeslotRepository->findForTeacher($this->authenticatedUser()),]);
            }

            if ($this->isGranted("ROLE_STUDENT") || $this->isGranted("ROLE_PARENT")) {
                return $this->render("@DH_EspT/User/UserInvite.twig", ["startTime" => $settings->getStart(), "endTime" => $settings->getEnd(), "regStart" => $settings->getRegStart(), "regEnd" => $settings->getRegEnd()]);
            }
        }

        if ($state == EventState::REGISTRATION) {
            if ($this->isGranted("ROLE_TEACHER")) {
                return $this->render("@DH_EspT/User/TeacherRegister.twig", ["regEnd" => $settings->getRegEnd(), "startTime" => $settings->getStart(), "endTime" => $settings->getEnd(),]);
            }

            if ($this->isGranted("ROLE_STUDENT") || $this->isGranted("ROLE_PARENT")) {
                return $this->render("@DH_EspT/User/UserRegister.twig", ["timeslots" => $timeslotRepository->findAll(), "regEnd" => $settings->getRegEnd(), "startTime" => $settings->getStart(), "endTime" => $settings->getEnd(),]);
            }
        }

        if ($state == EventState::PRINT) {
            if ($this->isGranted("ROLE_TEACHER")) {
                return $this->render("@DH_EspT/User/TeacherPrint.twig", ["startTime" => $settings->getStart(), "endTime" => $settings->getEnd(), "group" => $groupRepository->findFor($this->authenticatedUser()), "timeslots" => $timeslotRepository->findForTeacher($this->authenticatedUser()),]);
            }

            if ($this->isGranted("ROLE_STUDENT") || $this->isGranted("ROLE_PARENT")) {
                return $this->render("@DH_EspT/User/UserPrint.twig", ["startTime" => $settings->getStart(), "endTime" => $settings->getEnd(), "timeslots" => $timeslotRepository->findForUser($this->authenticatedUser()),]);
            }
        }

        return $this->render("@DH_EspT/User/None.twig");
    }

    /**
     * @param CreationSettingsRepository $settingsRepository
     * @param TimeslotRepository $timeslotRepository
     * @return Response
     * @Route("/timeslots", name="espt_timeslots")
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function timeslots(CreationSettingsRepository $settingsRepository, TimeslotRepository $timeslotRepository): Response {
        $settings = $settingsRepository->findFirst();
        $state = EventState::getState($settings);

        if ($this->isGranted("ROLE_TEACHER")) {
            if ($state == EventState::INVITE) {
                $timeslots = $timeslotRepository->findForTeacher($this->authenticatedUser());
                return $this->json($timeslots);
            }
        }

        if ($this->isGranted("ROLE_STUDENT") || $this->isGranted("ROLE_PARENT")) {
            if ($state == EventState::REGISTRATION) {
                $timeslots = $timeslotRepository->findAll();

                return $this->json($this->buildTimeslotArray($timeslots, $settings));
            }
        }

        return $this->json([]);
    }

    /**
     * @param int $id
     * @param TimeslotRepository $timeslotRepository
     * @param EntityManager $entityManager
     * @return Response
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @Route("/timeslots/reserve/{id}", name="espt_timeslots_reserve")
     */
    public function reserveTimeslot(int $id, TimeslotRepository $timeslotRepository, EntityManager $entityManager): Response {

        if (!$this->isGranted("ROLE_STUDENT") && !$this->isGranted("ROLE_PARENT")) {
            return $this->json([]);
        }

        $timeslot = $timeslotRepository->find($id);
        if ($timeslot == null) {
            return $this->json([]);
        }

        if ($timeslot->getType() != EventType::BOOK) return $this->json([]);

        if ($timeslot->getUser() != null) {
            if ($timeslot->getUser() === $this->authenticatedUser()) {

                $timeslot->setUser(null);
                $entityManager->persist($timeslot);
                $entityManager->flush();
                return $this->json([]);
            }

            return $this->json([]);
        }

        $timeslot->setUser($this->authenticatedUser());
        $entityManager->persist($timeslot);
        $entityManager->flush();

        return $this->json([]);
    }

    /**
     * @param Timeslot[] $timeslots
     * @param CreationSettings $settings
     * @return array
     */
    private function buildTimeslotArray(array $timeslots, CreationSettings $settings) : array {
        $events = array();
        foreach ($timeslots as $timeslot) {
            $data_timeslot = array();
            $data_timeslot['start'] = $timeslot->getStart();
            $data_timeslot['end'] = $timeslot->getEnd();
            $data_timeslot['id'] = $timeslot->getId();

            $color = 'red';
            $name = '';
            switch ($timeslot->getType()) {
                case EventType::BOOK:
                case EventType::INVITE :
                    $color = 'green';
                    $name = 'FREI';
                    break;
                case EventType::BREAK :
                    $color = 'gray';
                    $name = 'PAUSE';
                    break;
            }

            if ($timeslot->getUser() != null) {
                $name = 'BELEGT';
                $color = 'red';
                if ($timeslot->getUser() === $this->authenticatedUser()) {
                    $name = 'GEBUCHT';
                    $color = 'yellow';
                }
            }

            $data_timeslot['color'] = $color;
            $data_timeslot['name'] = $name;

            $data_event = array('events' => $data_timeslot);
            $data_event['id'] = $timeslot->getGroup()->getId();

            $usernames = '';
            /** @var User $user **/
            foreach ($timeslot->getGroup()->getUsers() as $user) {
                $usernames = $usernames . "\n" . $user->getNameByFirstname();
            }
            $data_event['title'] = $usernames;
            $data_event['subtitle'] = $timeslot->getGroup()->getRoom();

            $events = array_merge_recursive($events, $data_event);
        }
        $result = array();

        $result['events'] = $events;

        $settings = array('start' => $settings->getStart(), 'end' => $settings->getEnd());
        $result['settings'] = $settings;

        return $result;
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