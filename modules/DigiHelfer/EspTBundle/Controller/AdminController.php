<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\CreationSettings;
use DigiHelfer\EspTBundle\Entity\CreationSettingsRepository;
use DigiHelfer\EspTBundle\Entity\TeacherGroupRepository;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Entity\TimeslotRepository;
use DigiHelfer\EspTBundle\Entity\TimeslotTemplate;
use DigiHelfer\EspTBundle\Form\CreationSettingsType;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use IServ\CoreBundle\Controller\AbstractPageController;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
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
     * @return array
     * @throws Exception
     * @throws NonUniqueResultException
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

            //reset timeslots from last time
            $timeslotRepository->truncate();

            //create timeslots for all groups according to template
            $groups = $groupRepository->findAll();

            foreach ($groups as $group) {
                $templates = $group->getTimeslotTemplate()->getTimeslots();
                foreach ($templates as $template) {

                    /** @var TimeslotTemplate $template */
                    $timeslot = new Timeslot();
                    $timeslot->setStart($template->getStart());
                    $timeslot->setEnd($template->getEnd());
                    $timeslot->setType($template->getType());
                    $timeslot->setGroup($group);

                    $entityManager->persist($timeslot);
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