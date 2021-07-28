<?php


namespace DigiHelfer\EspTBundle\Entity;

use IServ\CrudBundle\Entity\CrudInterface;

/**
 * Class EventType
 * @package DigiHelfer\EspTBundle\Entity
 */
abstract class EventType {
    const BOOK = 0;
    const INVITE = 1;
    const BLOCKED = 2;
}