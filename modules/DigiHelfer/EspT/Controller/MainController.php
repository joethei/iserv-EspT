<?php

declare(strict_types=1);

namespace DigiHelfer\EspT\Controller;

use IServ\CrudBundle\Controller\AbstractBaseController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MainController
 * @package DigiHelfer\EspT\Controller
 * @Route("/espt")
 */
final class MainController extends AbstractBaseController {

    /**
     * @return array
     * @Route("", name="espt_index")
     * @Template("@EspT/index.html.twig")
     */
    public function index(): array {
        $this->addBreadcrumb("Elternsprechtag");
        return [];
    }
}