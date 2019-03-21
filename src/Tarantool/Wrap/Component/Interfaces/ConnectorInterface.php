<?php

namespace App\Tarantool\Wrap\Component\Interfaces;

use App\Tarantool\Wrap\Component\Connector;
use Tarantool\Client\Schema\Space;
use Tarantool\Mapper\Mapper;

interface ConnectorInterface
{
    public function getMapper(): Mapper;
    public function getSpace($spaceName): Space;
    public function setUserLastTrainingSpace(): Connector;
}