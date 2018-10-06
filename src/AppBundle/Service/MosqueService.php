<?php

namespace AppBundle\Service;

use AppBundle\Entity\Mosque;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Vich\UploaderBundle\Handler\AbstractHandler;

class MosqueService
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var AbstractHandler
     */
    private $vichUploadHandler;

    /**
     * @var MailService
     */
    private $mailService;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer, AbstractHandler $vichUploadHandler, MailService $mailService)
    {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->vichUploadHandler = $vichUploadHandler;
        $this->mailService = $mailService;
    }

    public function getCalendarList()
    {
        $res = $this->em
            ->getRepository("AppBundle:Mosque")
            ->createQueryBuilder("m")
            ->select("c.id, m.name, m.zipcode, m.city, m.country")
            ->innerJoin("m.configuration", "c")
            ->where("c.sourceCalcul = 'calendar'")
            ->andWhere("c.calendar IS NOT NULL")
            ->andWhere("m.city IS NOT NULL AND m.city != ''")
            ->andWhere("m.type = :mosqueType")
            ->groupBy("m.country, m.city, m.name")
            ->orderBy("m.country, m.city", "ASC")
            ->setParameter(':mosqueType', Mosque::TYPE_MOSQUE)
            ->getQuery()
            ->execute();

        $calendars = [];
        foreach ($res as $item) {
            $calendars[strtoupper($item["country"])][] = [
                'id' => $item["id"],
                'label' => ucfirst(strtolower($item["city"])) . ' ' . $item["zipcode"] . ' | ' . ucfirst(strtolower($item["name"])),
            ];
        }

        return $calendars;
    }

    /**
     * @param $query
     * @return array
     */
    public function searchApi($query)
    {
        $mosques = $this->em->getRepository("AppBundle:Mosque")
            ->publicSearch($query)
            ->getQuery()
            ->getResult();

        return $this->serializer->normalize($mosques, 'json', ["groups" => ["search"]]);
    }

    /**
     * @param Mosque $mosque
     * @throws @see MailService
     */
    public function validate(Mosque $mosque)
    {
        $mosque->setStatus(Mosque::STATUS_VALIDATED);
        $this->vichUploadHandler->remove($mosque, 'justificatoryFile');
        $mosque->setJustificatoryFile(null);
        $mosque->setJustificatory(null);
        $this->em->persist($mosque);
        $this->em->flush();
        $this->mailService->mosqueValidated($mosque);
    }

    /**
     * @param Mosque $mosque
     * @throws @see MailService
     */
    public function suspend(Mosque $mosque)
    {
        $mosque->setStatus(Mosque::STATUS_SUSPENDED);
        $this->em->persist($mosque);
        $this->em->flush();
        $this->mailService->mosqueSuspended($mosque);
    }

    /**
     * @param Mosque $mosque
     * @throws @see MailService
     */
    public function check(Mosque $mosque)
    {
        $mosque->setStatus(Mosque::STATUS_CHECK);
        $this->em->persist($mosque);
        $this->em->flush();
        $this->mailService->checkMosque($mosque);
    }

}
