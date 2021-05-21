<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\EventListener;

use DigiHelfer\EspTBundle\Security\Privilege;
use IServ\CoreBundle\Event\MenuEvent;
use IServ\CoreBundle\EventListener\MainMenuListenerInterface;

class MenuListener implements MainMenuListenerInterface
{
    /**
     * {@inheritDoc}
     */
    public function onBuildMainMenu(MenuEvent $event)
    {
        if (!$event->getAuthorizationChecker()->isGranted(Privilege::ADMIN)) {
            return;
        }

        $event->getMenu()->addChild('espt', [
            'route' => 'espt_index',
            'label' => _('EspT'),
            'extras' => [
                'icon' => 'interview',
                'icon_style' => 'iserv',
            ],
        ]);
    }
}