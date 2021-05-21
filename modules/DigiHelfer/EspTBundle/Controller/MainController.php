<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\Controller;

use DigiHelfer\EspTBundle\Entity\CreationSettings;
use DigiHelfer\EspTBundle\Entity\CreationSettingsType;
use IServ\CoreBundle\Controller\AbstractPageController;
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

        $form = $this->createForm(CreationSettingsType::class, $settings);

        return [
            'form' => $form->createView(),
        ];
    }
}