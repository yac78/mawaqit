<?php

namespace AppBundle\Command;

use AppBundle\Service\UserService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class MosqueScreenPhotoReminderCommand extends Command
{

    /**
     * @var UserService
     */
    private $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:remind-mosques-screen-photo')
            ->setDescription("Remind mosques to add screen photo");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->userService->remindUserToUploadScreenPhoto();

        $output->writeln("Reminder OK");
    }

}