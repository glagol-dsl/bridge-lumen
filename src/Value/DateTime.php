<?php
namespace Glagol\Bridge\Lumen\Value;

use DateTimeZone;

class DateTime extends \DateTimeImmutable implements \JsonSerializable
{
    /**
     * @param string $format
     * @param string $time
     * @param null|DateTimeZone $object
     * @return bool|\DateTimeImmutable|static
     */
    public static function createFromFormat($format, $time, $object = null)
    {
        $realDt = new static();
        $parentDt = parent::createFromFormat($format, $time, $object);

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