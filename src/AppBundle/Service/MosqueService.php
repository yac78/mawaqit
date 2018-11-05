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
     * @param $lat
     * @param $lon
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function searchApi($query, $lat, $lon)
    {
        $repo = $this->em->getRepository("AppBundle:Mosque");
        $mosques = [];

        if ($lon !== null && $lat !== null) {
            $statement = $this->em->getConnection()->prepare("SELECT m.id, m.name, m.phone, m.email, m.site, CONCAT(COALESCE(m.address, ''), ' ',  m.zipcode,' ', m.city, ' ', m.country_full_name) as localisation,  c.longitude , c.latitude, if(m.image1 is null, 'https://mawaqit.net/bundles/app/prayer-times/img/default.jpg', CONCAT('https://mawaqit.net/upload/', m.image1)) as image,  CONCAT('https://mawaqit.net/fr/', m.slug) as url, ROUND(get_distance_metres($lat, $lon, latitude, longitude) ,0) AS proximity  FROM configuration c INNER JOIN mosque m on m.configuration_id = c.id where m.status = 'VALIDATED' AND m.type = 'mosque' having proximity < 10000 ORDER BY proximity ASC  LIMIT 10");
            $statement->execute();
            $mosques = $statement->fetchAll();
        } else if ($query) {
            $mosques = $repo->publicSearch($query)
                ->getQuery()
                ->getResult();
            $mosques = $this->serializer->normalize($mosques, 'json', ["groups" => ["search"]]);
        }

        return $mosques;
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

    /**
     * @param Mosque $mosque
     * @throws @see MailService
     */
    public function duplicated(Mosque $mosque)
    {
        $mosque->setStatus(Mosque::STATUS_DUPLICATED);
        $this->em->persist($mosque);
        $this->em->flush();
        $this->mailService->duplicatedMosque($mosque);
    }

}
