<?php
declare(strict_types=1);
namespace Glagol\Bridge\Lumen\Entity;

trait JsonSerializeTrait
{
    public function jsonSerialize()
    {
        $properties = [];

        foreach ($this as $property => $value) {
            $properties[snake_case($property)] = $value;
        }

        return $properties;
    }
}
