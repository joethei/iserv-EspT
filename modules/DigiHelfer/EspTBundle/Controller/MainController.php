<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\TeacherGroupSelection;
use DigiHelfer\EspTBundle\Form\FilterGroupType;
use DigiHelfer\EspTBundle\Repository\CreationSettingsRepository;
use DigiHelfer\EspTBundle\Entity\EventState;
use DigiHelfer\EspTBundle\Repository\TeacherGroupRepository;
use DigiHelfer\EspTBundle\Repository\TimeslotRepository;
use DigiHelfer\EspTBundle\Security\Privilege;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use IServ\CoreBundle\Controller\AbstractPageController;
use IServ\Library\Config\Config;
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
     * @param Request $request
     * @param CreationSettingsRepository $creationSettingsRepository
     * @param TimeslotRepository $timeslotRepository
     * @param TeacherGroupRepository $groupRepository
     * @param EntityManagerInterface $entityManager
     * @param Config $config
     * @return Response
     * @throws NonUniqueResultException
     * @Route("/", name="espt_index")
     */
    public function index(Request $request, CreationSettingsRepository $creationSettingsRepository, TimeslotRepository $timeslotRepository, TeacherGroupRepository $groupRepository, EntityManagerInterface $entityManager, Config $config): Response {
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

            if ($this->isGranted(Privilege::STUDENT)) {
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

            if ($this->isGranted(Privilege::STUDENT)) {
                $groups = $groupRepository->findForSelection($this->authenticatedUser());
                if($groups === null) {
                    $groups = new TeacherGroupSelection();
                }

                $form = $this->createForm(FilterGroupType::class, $groups);

                $form->handleRequest($request);

                if($form->isSubmitted() && $form->isValid()) {
                    /**@var TeacherGroupSelection $groups*/
                    $groups = $form->getData();
                    $groups->setUser($this->authenticatedUser());

                    $entityManager->persist($groups);
                    $entityManager->flush();
                }

                return $this->render("@DH_EspT/User/UserRegister.twig", [
                    "timeslots" => $timeslotRepository->findForSelection($this->authenticatedUser()),
                    "regStart" => $settings->getRegStart(),
                    "regEnd" => $settings->getRegEnd(),
                    "startTime" => $settings->getStart(),
                    "endTime" => $settings->getEnd(),
                    "form" => $form->createView(),
                    ]);
            }
        }

        if ($state == EventState::PRINT) {
            if($this->isGranted(Privilege::TEACHER)) {
                $groups = $groupRepository->findFor($this->authenticatedUser());
                return $this->render("@DH_EspT/User/Print.twig", [
                    "groups" => $groups->toArray(),
                    "startTime" => $settings->getStart(),
                    "endTime" => $settings->getEnd(),
                    "allowEdit" => $config->get("EspTAllowEditByTeacher"),
                    "timeslots" => $timeslotRepository->findForTeacher($this->authenticatedUser()),
                ]);
            }
            return $this->render("@DH_EspT/User/Print.twig", [
                "startTime" => $settings->getStart(),
                "endTime" => $settings->getEnd(),
            ]);

        }

        return $this->render("@DH_EspT/User/None.twig");
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