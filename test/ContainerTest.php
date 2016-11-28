<?php
declare(strict_types = 1);
namespace GlagolTest\Binding\Laravel;

use Glagol\Binding\Laravel\Container;
use Illuminate\Contracts\Container\Container as IlluminateContainer;
use Psr\Container\ContainerInterface;
use Psr\Container\Exception\NotFoundException;

class ContainerTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldBindService()
    {
        /** @var IlluminateContainer $illuminateContainer */
        $illuminateContainer = $this->getMockBuilder(IlluminateContainer::class)
            ->getMock();

        $container = new Container($illuminateContainer);
        $container->bind(ContainerInterface::class, Container::class);
    }

    public function testShouldThrowExceptionWhenAccessingNonBoundService()
    {
        /** @var IlluminateContainer|\PHPUnit_Framework_MockObject_MockObject $illuminateContainer */
        $illuminateContainer = $this->getMockBuilder(IlluminateContainer::class)
            ->getMock();

        $illuminateContainer->method('bound')->willReturn(false);

        $container = new Container($illuminateContainer);

        $this->assertFalse($container->has(ContainerInterface::class));

        $this->expectException(NotFoundException::class);

        $container->get(ContainerInterface::class);
    }

    public function testShouldReturnBoundService()
    {
        /** @var IlluminateContainer|\PHPUnit_Framework_MockObject_MockObject $illuminateContainer */
        $illuminateContainer = $this->getMockBuilder(IlluminateContainer::class)
            ->getMock();

        $service = new \stdClass();

        $illuminateContainer->method('bound')->willReturn(true);
        $illuminateContainer->method('make')->willReturn($service);

        $container = new Container($illuminateContainer);

        $this->assertTrue($container->has('test'));

        $this->assertSame($service, $container->get('test'));
    }
}
