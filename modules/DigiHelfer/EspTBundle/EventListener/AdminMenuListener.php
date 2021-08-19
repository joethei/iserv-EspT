<?php

namespace DigiHelfer\EspTBundle\EventListener;

use IServ\AdminBundle\EventListener\AdminMenuListenerInterface;
use IServ\CoreBundle\Event\MenuEvent;

class AdminMenuListener implements AdminMenuListenerInterface {

    /**
     * {@inheritDoc}
     */
    public function onBuildAdminMenu(MenuEvent $event) : void {

        if(!$event->getAuthorizationChecker()->isGranted("PRIV_ESPT_ADMIN"))
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