<?php

namespace AppBundle\Command;

use Symfony\Component\Console\Command\Command;
use AppBundle\Repository\UserRepository;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ResetApiCountersCommand  extends Command
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
            ->setName('app:reset-api-counters')
            ->setDescription('Reset api call counters for all users having access token');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
       $output->writeln( $this->userRepository->resetApiCounters() . " compteurs d'appel api ont été remis à zéro");
    }

}