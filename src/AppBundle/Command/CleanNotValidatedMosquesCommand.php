<?php

namespace AppBundle\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CleanNotValidatedMosquesCommand  extends Command
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setName('app:clean-mosques')
            ->setDescription('Remove not validated mosques if no response after 15 days');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
       $output->writeln( $this->em->getRepository('AppBundle:Mosque')->removeNotValidated() . ' ont été supprimée(s)');
    }

}