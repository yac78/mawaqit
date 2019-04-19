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
            ->orderBy("m.countryFullName, m.city", "ASC")
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
        if (empty($query) && empty($lat) && empty($lon)) {
            return [];
        }

        $q = "SELECT id, name, phone, email, site, 
                      CONCAT(COALESCE(address, ''), ' ', zipcode,' ', city, ' ', country_full_name) as localisation,  
                      longitude , latitude, 
                      if(image1 is null, 'https://mawaqit.net/bundles/app/prayer-times/img/default.jpg', CONCAT('https://mawaqit.net/upload/', image1)) as image,  
                      CONCAT('https://mawaqit.net/fr/', slug) as url,                         
                      women_space AS womenSpace, 
                      janaza_prayer AS janazaPrayer, 
                      aid_prayer AS aidPrayer, 
                      children_courses AS childrenCourses, 
                      adult_courses AS adultCourses, 
                      ramadan_meal AS ramadanMeal, 
                      handicap_accessibility AS handicapAccessibility, 
                      ablutions, 
                      parking";

        if ($lon !== null && $lat !== null) {
            $q .= ",ROUND(get_distance_metres($lat, $lon, latitude, longitude) ,0) AS proximity
                            FROM mosque 
                            WHERE status = 'VALIDATED' AND type = 'mosque' 
                            HAVING proximity < 10000 ORDER BY proximity ASC  LIMIT 10";
        } else if ($query) {
            $query = preg_split("/\s+/", trim($query));
            $q .= " FROM mosque WHERE status = 'VALIDATED' AND type = 'mosque'";
            foreach ($query as $key => $keyword) {
                $q .= " AND (name LIKE :keyword$key 
                OR association_name LIKE :keyword$key 
                OR address LIKE :keyword$key 
                OR city LIKE :keyword$key 
                OR zipcode LIKE :keyword$key 
                OR country LIKE :keyword$key)";
            }
        }

        $stmt = $this->em->getConnection()->prepare($q);

        if ($lon !== null && $lat !== null) {
            $stmt->bindValue(":lat", $lat);
            $stmt->bindValue(":lon", $lon);
        } else if ($query) {
            foreach ($query as $key => $keyword) {
                $stmt->bindValue(":keyword$key", "%$keyword%");
            }
        }

        $stmt->execute();
        return $stmt->fetchAll();
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
