<?php


namespace DigiHelfer\EspTBundle\Entity;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/**
 * Class EventType
 * @package DigiHelfer\EspTBundle\Entity
 */
abstract class EventType extends Type {
    const EVENTTYPE = 'espt_eventtype';
    const BOOK = 'book';
    const INVITE = 'invite';
    const BLOCKED = 'blocked';
    const BREAK = 'break';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string {
        return "ENUM ('invite', 'break', 'book', 'block')";
    }

    public function convertToPHPValue($value, AbstractPlatform $platform) {
        return $value;
    }

    public function getName(): string {
        return self::EVENTTYPE;
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform): bool {
        return true;
    }
}