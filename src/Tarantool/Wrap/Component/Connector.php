<?php
/**
 * @author koshpaevsv
 */

namespace App\Tarantool\Wrap\Component;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Tarantool\Client\Client;
use Tarantool\Client\Connection\StreamConnection;
use Tarantool\Client\Packer\PurePacker;
use Tarantool\Client\Schema\Space;
use Tarantool\Mapper\Mapper;

class Connector
{
    /** @var StreamConnection */
    private $connection;

    /** @var Client */
    private $client;

    /** @var Mapper */
    private $mapper;

    /** @var ContainerInterface  */
    private $container;

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     * @return Connector
     */
    public function setContainer(ContainerInterface $container): Connector
    {
        $this->container = $container;
        return $this;
    }
    /**
     * @return StreamConnection
     */
    public function getConnection(): StreamConnection
    {
        return $this->connection;
    }

    /**
     * @param StreamConnection $connection
     * @return Connector
     */
    public function setConnection(StreamConnection $connection): Connector
    {
        $this->connection = $connection;
        return $this;
    }

    /**
     * @return Client
     */
    public function getClient(): Client
    {
        return $this->client;
    }

    /**
     * @param Client $client
     * @return Connector
     */
    public function setClient(Client $client): Connector
    {
        $this->client = $client;
        return $this;
    }

    /**
     * @return Mapper
     */
    public function getMapper(): Mapper
    {
        return $this->mapper;
    }

    /**
     * @param Mapper $mapper
     * @return Connector
     */
    public function setMapper(Mapper $mapper): Connector
    {
        $this->mapper = $mapper;
        return $this;
    }

    /**
     * Connector constructor.
     * @param ContainerInterface $container
     */
    public function __construct(
        ContainerInterface $container
    )
    {
        $this->container = $container;
        $this->setConnection(
            new StreamConnection(
                $this
                    ->getContainer()
                    ->getParameter('tarantool.connection')
            )
        )
            ->setClient(new Client($this->getConnection(),new PurePacker()))
            ->setMapper(new Mapper($this->client));
    }

    /**
     * @param $spaceName
     * @return \Tarantool\Client\Schema\Space
     */
    public function getSpace($spaceName): Space
    {
        return $this->getClient()->getSpace($spaceName);
    }

}