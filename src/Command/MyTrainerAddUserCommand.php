<?php

namespace App\Command;

use App\DataFixtures\AppFixtures;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MyTrainerAddUserCommand extends ContainerAwareCommand
{
    protected static $defaultName = 'my_trainer:add_user';

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('name', InputArgument::REQUIRED, 'Name of new User')
            ->addArgument('password', InputArgument::REQUIRED, 'Password of new user')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $name = $input->getArgument('name');
        $pass = $input->getArgument('password');

        try {
//            $fixture = new AppFixtures($this->getContainer()->get('security.user_password_encoder.generic'));
//            $fixture->load($this->getContainer()->get('doctrine.orm.default_entity_manager'));
            $io->success('Success');
        } catch (\Exception $e) {
//            $io->error("Something wrong");
//            $io->note($e);
        }



    }


}
