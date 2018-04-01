<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use AppBundle\Repository\UserRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CleanOldUsersCommand  extends Command
{

    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:clean-old-users')
            ->setDescription('Remove older disabled users > one month');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
       $output->writeln( $this->userRepository->removeOldDisbaledUsers() . ' ont été supprimé(s)');
    }

}