<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\CreationSettingsRepository;
use DigiHelfer\EspTBundle\Entity\State;
use IServ\CoreBundle\Controller\AbstractPageController;
use Knp\Menu\FactoryInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @package DigiHelfer\EspTBundle\Controller
 * @Route("/espt")
 */
final class MainController extends AbstractPageController {

    /**
     * @param CreationSettingsRepository $creationSettingsRepository
     * @return array
     * @Route("/", name="espt_index")
     * @Template("@DH_EspT/Default/teachers.html.twig")
     */
    public function index(CreationSettingsRepository $creationSettingsRepository): array {
        $this->addBreadcrumb(_("EspT"));

        $settings = $creationSettingsRepository->findFirst();
        if ($settings == null || $settings->getEnd()) {
            return [
                "state" => State::NONE,
            ];
        }

        return [
            "state" => State::REGISTRATION,
            "startTime" => $settings->getStart(),
            "endTime" => $settings->getEnd(),
            "regEnd" => $settings->getRegEnd(),
            "groups" => [],
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