<?php

declare(strict_types=1);

namespace DigiHelfer\EspTBundle\EventListener;

use IServ\CoreBundle\Event\MenuEvent;
use IServ\CoreBundle\EventListener\MainMenuListenerInterface;

class MenuListener implements MainMenuListenerInterface
{
    /**
     * {@inheritDoc}
     */
    public function onBuildMainMenu(MenuEvent $event)
    {
        $event->getMenu()->addChild('espt', [
            'route' => 'espt_index',
            'label' => _('EspT'),
            'extras' => [
                'icon' => 'calendar',
                'icon_style' => 'iserv',
            ],
        ]);
    }
}