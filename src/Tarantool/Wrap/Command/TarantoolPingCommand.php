<?php

namespace App\Tarantool\Wrap\Command;

use App\Tarantool\Wrap\Component\Connector;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TarantoolPingCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'tarantool:ping';

    protected function configure()
    {
        $this
            ->setDescription('Ping to Tarantool')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $connector = $this->getContainer()->get("tarantool.wrap");

        try {
            $connector->getClient()->ping();
            $io->success('Ping is success!');
        } catch (\Exception $e) {
            $io->error('No ping!');
            $io->caution($e);
        }
    }
}
