<?php
namespace Glagol\Bridge\Lumen\Value;

use DateTimeZone;

class DateTime extends \DateTimeImmutable implements \JsonSerializable
{
    public static function createFromFormat($format, $time, DateTimeZone $timezone = null)
    {
        $realDt = new static();
        $parentDt = parent::createFromFormat($format, $time, $timezone);

        if (!$parentDt) {
            return false;
        }

        $realDt->setTimestamp($parentDt->getTimestamp());
        return $realDt;
    }

    public function jsonSerialize()
    {
        return $this->format('c');
    }
}