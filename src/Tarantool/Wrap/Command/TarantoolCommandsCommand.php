<?php

namespace App\Tarantool\Wrap\Command;

use App\Tarantool\Wrap\Component\Connector;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TarantoolCommandsCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'tarantool:commands';

    /** @var Connector */
    private $connector;

    /**
     * @return Connector
     */
    public function getConnector(): Connector
    {
        return $this->connector;
    }

    /**
     * @param Connector $connector
     * @return TarantoolCommandsCommand
     */
    public function setConnector(Connector $connector): TarantoolCommandsCommand
    {
        $this->connector = $connector;
        return $this;
    }

    public function initialize(InputInterface $input, OutputInterface $output)
    {
        $this->setConnector($this->getContainer()->get('tarantool.wrap'));
    }

    protected function configure()
    {
        $this
            ->setDescription('Common commands')
            ->addArgument('tarantool_command', InputArgument::REQUIRED, 'Command itself')
            ->addArgument('name', InputArgument::OPTIONAL, 'Name of new space')
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|null|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $command = $input->getArgument('tarantool_command');
        $name = $input->getArgument('name');

        if ($command) {
            switch ($command) {
                case 'set_space':
                    try {
                        $this->getConnector()->getClient()->evaluate("s=box.schema.create_space('$name')");
                        $this->getConnector()->getClient()->evaluate("s:create_index('primary',{type = 'hash',parts = {1, 'unsigned'}})");
                        $io->success("New $name created!");
                    } catch (\Exception $e) {
                        $io->error('New space was not created. Check the connection or name of space.');
                        $io->note($e);
                    }
                    break;
                case 'select_tuple':
                    $io->title('JSON_ENCODE_RESULT');
                    $io->text(json_encode($this->getConnector()->getSpace($name)->select([1])->getData()));
                    break;
                default:
                    $io->error("Unknown command");
            }
        }

    }
}
