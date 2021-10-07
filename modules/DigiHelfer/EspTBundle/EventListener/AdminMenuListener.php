<?php

namespace DigiHelfer\EspTBundle\EventListener;

use DigiHelfer\EspTBundle\Security\Privilege;
use IServ\AdminBundle\EventListener\AdminMenuListenerInterface;
use IServ\CoreBundle\Event\MenuEvent;

class AdminMenuListener implements AdminMenuListenerInterface {

    /**
     * {@inheritDoc}
     */
    public function onBuildAdminMenu(MenuEvent $event) : void {
        if(!$event->getAuthorizationChecker()->isGranted(Privilege::ADMIN))
            return;

        $event->getMenu()->getChild(self::ADMIN_MODULES)->addChild('espt_config', [
            'route' => 'espt_admin_settings',
            'label' => _('EspT'),
            'extras' => [
                'icon' => 'clock',
                'icon_style' => 'fugue',
                'orderNumber' => 30,
            ],
        ]);
    }

}