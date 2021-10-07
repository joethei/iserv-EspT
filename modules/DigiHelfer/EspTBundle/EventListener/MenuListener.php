<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\EventListener;

use DigiHelfer\EspTBundle\Security\Privilege;
use IServ\CoreBundle\Event\MenuEvent;
use IServ\CoreBundle\EventListener\MainMenuListenerInterface;

class MenuListener implements MainMenuListenerInterface {
    /**
     * {@inheritDoc}
     */
    public function onBuildMainMenu(MenuEvent $event) {
        if(!$event->getAuthorizationChecker()->isGranted(Privilege::ADMIN)
        && !$event->getAuthorizationChecker()->isGranted(Privilege::TEACHER)
        && !$event->getAuthorizationChecker()->isGranted(Privilege::STUDENT))
            return;


        $event->getMenu()
            ->addChild('espt', ['route' => 'espt_index', 'label' => _('EspT')])
            ->setExtra('icon', 'espt')
            ->setExtra('icon_style', 'iserv')
            ->setExtra('icon_fallback', 'users')
            ->setExtra('icon_fallback_style', 'fugue')
        ;
    }
}