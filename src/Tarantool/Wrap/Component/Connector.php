<?php
/**
 * @author koshpaevsv
 */

namespace App\Tarantool\Wrap\Component;

use App\Tarantool\Wrap\Component\Interfaces\ConnectorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Tarantool\Client\Client;
use Tarantool\Client\Connection\StreamConnection;
use Tarantool\Client\Packer\PurePacker;
use Tarantool\Client\Schema\Space;
use Tarantool\Mapper\Mapper;

class Connector implements ConnectorInterface
{
    /** @var StreamConnection */
    private $connection;

    /** @var Client */
    private $client;

    /** @var Mapper */
    private $mapper;

    /** @var ContainerInterface  */
    private $container;

    private $authenticatedClient;

    /**
     * @return Mapper
     */
    public function getMapper(): Mapper
    {
        return $this->mapper;
    }

    /**
     * Connector constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container )
    {
        $this->container = $container;
        $this->connection  = new StreamConnection( $container->getParameter('tarantool.connection') );
        $this->client = new Client($this->connection,new PurePacker());
        $this->mapper = new Mapper($this->client);
        $this->authenticatedClient = $this->client->authenticate(
            $container->getParameter('tarantool_user'),
            $container->getParameter('tarantool_pass')
        );
    }

    /**
     * @param $spaceName
     * @return \Tarantool\Client\Schema\Space
     */
    public function getSpace($spaceName): Space
    {
        return $this->client->getSpace($spaceName);
    }

    /**
     * @return Connector
     * @throws \Exception
     */
    public function setUserLastTrainingSpace(): self
    {
        /** @var Mapper $mapper */
        $mapper = $this->mapper->getSchema()->createSpace('user_last_training_day');
        $mapper->addProperties([
            'user_id' => 'unsigned',
            'day_id' => 'string',
            'creation_date' => 'string',
            'main_time' => 'string',
        ]);
        $mapper->createIndex([
            'type' => 'hash',
            'fields' => ['user_id'],
        ]);

        return $this;
    }
}