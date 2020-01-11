<?php

namespace AppBundle\Command;

use AppBundle\Entity\Message;
use AppBundle\Entity\Mosque;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CleanPhotoCommand extends Command
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
        $this->setName('app:clean-photo');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $files = glob(__DIR__ . "/../../../web/upload/*");

        $filesToDelete = [];

        foreach ($files as $file) {
            $name = basename($file);

            $mosque = $this->em->createQueryBuilder()
                ->select("m")
                ->from(Mosque::class, "m")
                ->where("m.image1 = '$name'")
                ->orWhere("m.image2 = '$name'")
                ->orWhere("m.image3 = '$name'")
                ->orWhere("m.justificatory = '$name'")
                ->getQuery()
                ->getResult();

            $message = $this->em->createQueryBuilder()
                ->select("m")
                ->from(Message::class, "m")
                ->where("m.image = '$name'")
                ->getQuery()
                ->getResult();

            if(empty($mosque) && empty($message))
            {
                if(unlink($file))
                {
                    $filesToDelete[] = $file;
                }
            }
        }

        $output->writeln( count($filesToDelete) . " removed");
    }
}