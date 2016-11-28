<?php
declare(strict_types = 1);
namespace Glagol\Binding\Laravel;

use Glagol\Contract\Container\ServiceBind;
use Illuminate\Contracts\Container\Container as IlluminateContainer;
use Psr\Container\ContainerInterface;

/**
 * {@inheritDoc}
 */
class Container implements ContainerInterface, ServiceBind
{
    /**
     * @var IlluminateContainer
     */
    private $container;

    public function __construct(IlluminateContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @inheritdoc
     */
    public function get($id)
    {
        if (!$this->has($id)) {
            throw new NotFoundException("Service by the name of '{$id}' was not found");
        }

        return $this->container->make($id);
    }

    /**
     * @inheritdoc
     */
    public function has($id)
    {
        return $this->container->bound($id);
    }

    /**
     * @inheritdoc
     */
    public function bind($id, $concrete = null, $shared = false)
    {
        $this->container->bind($id, $concrete, $shared);
    }
}
