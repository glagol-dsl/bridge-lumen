<?php
declare(strict_types=1);

namespace Glagol\Binding\Lumen\Doctrine;

use LaravelDoctrine\ORM\Configuration\Connections\Connection;

class MysqliConnection extends Connection
{
    /**
     * @param array $settings
     *
     * @return mixed
     */
    public function resolve(array $settings = [])
    {
        return [
            'driver'                => 'mysqli',
            'host'                  => array_get($settings, 'host'),
            'dbname'                => array_get($settings, 'database'),
            'user'                  => array_get($settings, 'username'),
            'password'              => array_get($settings, 'password'),
            'charset'               => array_get($settings, 'charset'),
            'port'                  => array_get($settings, 'port'),
            'unix_socket'           => array_get($settings, 'unix_socket'),
            'prefix'                => array_get($settings, 'prefix'),
            'defaultTableOptions'   => array_get($settings, 'defaultTableOptions', []),
        ];
    }
}
