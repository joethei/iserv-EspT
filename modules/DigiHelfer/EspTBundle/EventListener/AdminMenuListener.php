<?php

namespace DigiHelfer\EspTBundle\EventListener;

use IServ\AdminBundle\EventListener\AdminMenuListenerInterface;
use IServ\CoreBundle\Event\MenuEvent;

class AdminMenuListener implements AdminMenuListenerInterface {

    /**
     * {@inheritDoc}
     */
    public function onBuildAdminMenu(MenuEvent $event) {
        $event->getMenu()->getChild(self::ADMIN_MODULES)->addChild('espt_config', [
            'route' => 'espt_admin_teachergroup_index',
            'label' => _('EspT'),
            'extras' => [
                'icon' => 'clipboard',
                'icon_style' => 'fugue',
                'orderNumber' => 30,
            ],
        ]);
    }

}