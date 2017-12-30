<?php
declare(strict_types=1);
namespace Glagol\Bridge\Lumen\Entity;

use Doctrine\ORM\EntityManagerInterface;

abstract class EntityRepository
{
    /**
     * @var EntityManagerInterface
     */
    protected $_em;

    /**
     * Initializes a new repository
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->_em = $em;
        $this->_init();
    }

    protected function _init()
    {
    }
}
