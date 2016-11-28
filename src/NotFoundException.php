<?php
declare(strict_types = 1);
namespace Glagol\Binding\Laravel;

use Psr\Container\Exception\NotFoundException as INotFoundException;
use RuntimeException;

class NotFoundException extends RuntimeException implements INotFoundException
{
}