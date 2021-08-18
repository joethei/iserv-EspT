<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\CreationSettings;
use DigiHelfer\EspTBundle\Entity\TeacherGroupRepository;
use DigiHelfer\EspTBundle\Entity\Timeslot;
use DigiHelfer\EspTBundle\Entity\TimeslotRepository;
use DigiHelfer\EspTBundle\Entity\TimeslotTemplate;
use DigiHelfer\EspTBundle\Form\EventSettingsType;
use Doctrine\ORM\EntityManagerInterface;
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
     * @throws \Doctrine\DBAL\Exception
     * @Route("/settings", name="espt_admin_settings")
     * @Template("@DH_EspT/Default/index.html.twig")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, TeacherGroupRepository $groupRepository, TimeslotRepository $timeslotRepository): array {
        $this->addBreadcrumb(_("EspT"));

        $settings = new CreationSettings();

        //set default form values
        $settings->setStart(new \DateTime('tomorrow'));
        $settings->setEnd(new \DateTime('tomorrow'));
        $settings->setRegStart(new \DateTime('tomorrow'));
        $settings->setRegEnd(new \DateTime('tomorrow'));

        $form = $this->createForm(EventSettingsType::class, $settings);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //reset timeslots from last time
            $timeslotRepository->clear();

            //create timeslots for all groups according to template
            $groups = $groupRepository->findAll();
            foreach ($groups as $group) {
                foreach ($group->getTimeslotTemplate()->getTimeslots() as $template) {

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
            'menu' => $this->getMenu('Einstellungen')
        ];
    }

    private function getMenu(?string $current = null): ItemInterface {
        $menu = $this->get(FactoryInterface::class)->createItem('root');
        $menu->addChild('Gruppen', ['route' => 'espt_admin_teachergroup_index']);
        $menu->addChild('Zeitfenster', ['route' => 'espt_admin_timeslots_index']);
        $menu->addChild('Einstellungen', ['route' => 'espt_admin_settings']);

        if (null !== $current) {
            if (null === $item = $menu->getChild($current)) {
                throw new \LogicException(sprintf('No child "%s" found!', $current));
            }

            $item->setCurrent(true);
        }

        return $menu;
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