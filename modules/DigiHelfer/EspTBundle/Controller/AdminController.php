<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\CreationSettings;
use DigiHelfer\EspTBundle\Repository\CreationSettingsRepository;
use DigiHelfer\EspTBundle\Repository\TeacherGroupRepository;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Repository\TimeslotRepository;
use DigiHelfer\EspTBundle\Entity\TimeslotTemplate;
use DigiHelfer\EspTBundle\Form\CreationSettingsType;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use IServ\CoreBundle\Controller\AbstractPageController;
use Knp\Menu\FactoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package DigiHelfer\EspTBundle\Controller
 * @Route("/admin/espt", name="espt_admin")
 */
class AdminController extends AbstractPageController {

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param TeacherGroupRepository $groupRepository
     * @param TimeslotRepository $timeslotRepository
     * @param CreationSettingsRepository $settingsRepository
     * @return array
     * @throws Exception
     * @throws NonUniqueResultException
     * @throws \Exception
     * @Route("/settings", name="_settings")
     * @Template("@DH_EspT/AdminMenu.html.twig")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, TeacherGroupRepository $groupRepository, TimeslotRepository $timeslotRepository, CreationSettingsRepository $settingsRepository): array {
        $this->addBreadcrumb(_("EspT"));

        $settings = $settingsRepository->findFirst();
        if($settings === null) {
            $settings = new CreationSettings();

            //set default form values
            $settings->setStart(new \DateTimeImmutable('tomorrow'));
            $settings->setEnd(new \DateTimeImmutable('tomorrow'));
            $settings->setRegStart(new \DateTimeImmutable('tomorrow'));
            $settings->setRegEnd(new \DateTimeImmutable('tomorrow'));
        }


        $form = $this->createForm(CreationSettingsType::class, $settings);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**@var CreationSettings $settings*/
            $settings = $form->getData();

            //reset timeslots from last time
            $timeslotRepository->truncate();

            //create timeslots for all groups according to templates
            $groups = $groupRepository->findAll();

            foreach ($groups as $group) {
                $templates = $group->getTimeslotTemplates();
                foreach($templates as $template) {
                    foreach ($template->getTimeslots() as $timeslotTemplate) {
                        $startDate = $settings->getStart();
                        $startDate->add(new \DateInterval('P' . ($template->getDay() - 1) . 'D'));

                        $day = (int)$startDate->format("j");
                        $month = (int)$startDate->format("m");
                        $year = (int)$startDate->format("Y");

                        /** @var TimeslotTemplate $timeslotTemplate */
                        $timeslot = new Timeslot();
                        $startDate = new \DateTimeImmutable();
                        $startDate->setDate($year, $month, $day);
                        $startDate->setTime((int)$timeslotTemplate->getStart()->format("H"), (int)$timeslotTemplate->getStart()->format("i"));

                        $endDate = new \DateTimeImmutable();
                        $endDate->setDate($year, $month, $day);
                        $endDate->setTime((int)$timeslotTemplate->getEnd()->format('H'), (int)$timeslotTemplate->getEnd()->format('i'));

                        $timeslot->setStart($startDate);
                        $timeslot->setEnd($endDate);
                        $timeslot->setType($timeslotTemplate->getType());
                        $timeslot->setGroup($group);

                        $entityManager->persist($timeslot);
                    }
                }
            }
            $entityManager->persist($settings);
            $entityManager->flush();
        }

        return [
            'form' => $form->createView(),
            'index' => true
        ];
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