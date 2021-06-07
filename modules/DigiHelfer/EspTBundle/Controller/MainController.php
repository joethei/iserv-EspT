<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\CreationSettings;
use DigiHelfer\EspTBundle\Entity\CreationSettingsType;
use IServ\CoreBundle\Controller\AbstractPageController;
use IServ\CoreBundle\Repository\GroupRepository;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @package DigiHelfer\EspTBundle\Controller
 * @Route("/espt")
 */
final class MainController extends AbstractPageController {

    /**
     * @return array
     * @Route("", name="espt_index")
     * @Template("@DH_EspT/Default/index.html.twig")
     */
    public function index(): array {
        $this->addBreadcrumb(_("EspT"));

        $settings = new CreationSettings();

        //set default form values
        $settings->setDate(new \DateTime('tomorrow'));
        $settings->setStart(new \DateTime('tomorrow'));
        $settings->setEnd(new \DateTime('tomorrow'));
        $settings->setNormalLength(\DateInterval::createFromDateString('15 minutes'));
        $settings->setInviteLength(\DateInterval::createFromDateString('15 minutes'));
        $settings->setMaxNumberOfInvites(15);
        $settings->setRegStart(new \DateTime('tomorrow'));
        $settings->setRegEnd(new \DateTime('tomorrow'));

        $form = $this->createForm(CreationSettingsType::class, $settings);

        return [
            'form' => $form->createView(),
            'menu' => $this->getMenu('Erstellung')
        ];
    }

    /**
     * @param GroupRepository $repository
     * @return array
     * @Route("/teachers", name="espt_teachers")
     * @Template("@DH_EspT/Default/teachers.html.twig")
     */
    public function teachers(GroupRepository $repository): array {
        $this->addBreadcrumb(_("EspT"));

        $teachers = $repository->findByNameOrAccount("Lehrer")->getUsers();

        return ['teachers' => $teachers];
    }

    private function getMenu(?string $current = null): ItemInterface
    {
        $menu = $this->get(FactoryInterface::class)->createItem('root');
        $menu->addChild('Erstellung', ['route' => 'espt_index']);
        $menu->addChild('Lehrkräfte', ['route' => 'espt_teachers']);

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