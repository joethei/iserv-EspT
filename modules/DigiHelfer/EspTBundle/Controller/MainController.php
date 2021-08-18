<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\CreationSettingsRepository;
use DigiHelfer\EspTBundle\Entity\EventType;
use DigiHelfer\EspTBundle\Entity\State;
use DigiHelfer\EspTBundle\Entity\TeacherGroup;
use DigiHelfer\EspTBundle\Entity\TeacherGroupRepository;
use DigiHelfer\EspTBundle\Entity\TimeslotRepository;
use Doctrine\ORM\EntityManager;
use IServ\CoreBundle\Controller\AbstractPageController;
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
     * @param TimeslotRepository $timeslotRepository
     * @return Response
     * @Route("/", name="espt_index")
     */
    public function index(CreationSettingsRepository $creationSettingsRepository, TeacherGroupRepository $groupRepository, TimeslotRepository $timeslotRepository): Response {
        $this->addBreadcrumb(_("EspT"));

        $settings = $creationSettingsRepository->findFirst();
        $state = State::getState($settings);

        if ($state == State::NONE) {
            return $this->render("@DH_EspT/User/None.twig");
        }

        if ($state == State::INVITE) {
            if($this->isGranted("ROLE_TEACHER")) {
                return $this->render("@DH_EspT/User/TeacherInvite.twig" , [
                    "startTime" => $settings->getStart(),
                    "endTime" => $settings->getEnd(),
                    "regStart" => $settings->getRegStart(),
                    "regEnd" => $settings->getRegEnd(),
                    "timeslots" => $timeslotRepository->findForTeacher($this->authenticatedUser()),
                ]);
            }

            if($this->isGranted("ROLE_STUDENT") || $this->isGranted("ROLE_PARENT")) {
                return $this->render("@DH_EspT/User/UserInvite.twig", [
                    "startTime" => $settings->getStart(),
                    "endTime" => $settings->getEnd(),
                    "regStart" => $settings->getRegStart(),
                    "regEnd" => $settings->getRegEnd()
                ]);
            }
        }

        if ($state == State::REGISTRATION) {
            if($this->isGranted("ROLE_TEACHER")) {
                return $this->render("@DH_EspT/User/TeacherRegister.twig", [
                    "regEnd" => $settings->getRegEnd(),
                    "start" => $settings->getStart(),
                    "end" => $settings->getEnd(),
                ]);
            }

            if($this->isGranted("ROLE_STUDENT") || $this->isGranted("ROLE_PARENT")) {
                return $this->render("@DH_EspT/User/UserRegister.twig" ,[
                    "timeslots" => $timeslotRepository->findAll(),
                    "regEnd" => $settings->getRegEnd(),
                    "start" => $settings->getStart(),
                ]);
            }
        }

        if($state == State::PRINT) {
            if($this->isGranted("ROLE_TEACHER")) {
                return $this->render("@DH_EspT/User/TeacherPrint.twig", [
                    "start" => $settings->getStart(),
                    "end" => $settings->getEnd(),
                    "group" => $groupRepository->findFor($this->authenticatedUser()),
                    "timeslots" => $timeslotRepository->findForTeacher($this->authenticatedUser()),
                ]);
            }

            if($this->isGranted("ROLE_STUDENT") || $this->isGranted("ROLE_PARENT")) {
                return $this->render("@DH_EspT/User/UserPrint.twig" ,[
                    "start" => $settings->getStart(),
                    "end" => $settings->getEnd(),
                    "timeslots" => $timeslotRepository->findForUser($this->authenticatedUser()),
                ]);
            }
        }

       return $this->render("@DH_EspT/User/None.twig");
    }

    /**
     * @param CreationSettingsRepository $settingsRepository
     * @param TimeslotRepository $timeslotRepository
     * @return Response
     * @Route("/timeslots", name="espt_timeslots")
     */
    public function timeslots(CreationSettingsRepository $settingsRepository, TimeslotRepository $timeslotRepository) : Response {
        $settings = $settingsRepository->findFirst();
        $state = State::getState($settings);

        if($state == State::NONE) {
            return $this->json([]);
        }

        if($this->isGranted("ROLE_TEACHER")) {
            if($state == State::INVITE)
                return $this->json($timeslotRepository->findForTeacher($this->authenticatedUser()));
        }

        if($this->isGranted("ROLE_STUDENT") || $this->isGranted("ROLE_PARENT")) {
            if($state == State::REGISTRATION)
                return $this->json($timeslotRepository->findAll());
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
     * @Route("/timeslots/reserve/{id}", name="espt_timeslots/reserve")
     */
    public function reserveTimeslot(int $id, TimeslotRepository $timeslotRepository, EntityManager $entityManager) : Response {

        if(!$this->isGranted("ROLE_STUDENT") && !$this->isGranted("ROLE_PARENT")) {
            return $this->json([]);
        }

        $timeslot = $timeslotRepository->find($id);
        if($timeslot == null) {
            return $this->json([]);
        }

        if($timeslot->getType() != EventType::BOOK)
            return $this->json([]);

        if($timeslot->getUser() != null) {
            if($timeslot->getUser() === $this->authenticatedUser()) {

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