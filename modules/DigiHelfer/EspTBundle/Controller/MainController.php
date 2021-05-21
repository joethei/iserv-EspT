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

        $settings->setDate(new \DateTime('tomorrow'));
        $settings->setStart(new \DateTime('tomorrow'));
        $settings->setEnd(new \DateTime('tomorrow'));
        $settings->setNormalLength(new \DateInterval('15 minutes'));
        $settings->setInviteLength(new \DateInterval('15 minutes'));
        $settings->setMaxNumberOfInvites(15);
        $settings->setRegStart(new \DateTime('tomorrow'));
        $settings->setRegEnd(new \DateTime('tomorrow'));

        $form = $this->createForm(CreationSettingsType::class, $settings);

        return [
            'form' => $form->createView(),
        ];
    }
}