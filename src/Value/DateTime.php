<?php
namespace Glagol\Bridge\Lumen\Value;

class DateTime extends \DateTimeImmutable implements \JsonSerializable
{
    public function jsonSerialize()
    {
        return $this->format('c');
    }
}