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

    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        AbstractHandler $vichUploadHandler,
        MailService $mailService
    ) {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->vichUploadHandler = $vichUploadHandler;
        $this->mailService = $mailService;
    }

    /**
     * @param $word
     * @param $lat
     * @param $lon
     *
     * @return mixed
     * @throws \Doctrine\DBAL\DBALException
     */
    public function searchApi($word, $lat, $lon)
    {
        if (strlen($word) < 3 && (empty($lat) || empty($lon))) {
            return [];
        }

        $q = "SELECT m.id, m.name, m.phone, m.email, m.site, 
                      CONCAT(COALESCE(m.address, ''), ' ', m.zipcode,' ', m.city, ' ', m.country_full_name) as localisation,  
                      m.longitude , m.latitude, 
                      if(m.image1 is null, 'https://mawaqit.net/bundles/app/prayer-times/img/default.jpg', CONCAT('https://mawaqit.net/upload/', m.image1)) as image,  
                      CONCAT('https://mawaqit.net/fr/', m.slug) as url,
                      IF(c.jumua, c.jumua_time, null) as jumua,                         
                      IF(c.jumua, c.jumua_time2, null) as jumua2,                         
                      m.women_space AS womenSpace, 
                      m.janaza_prayer AS janazaPrayer, 
                      m.aid_prayer AS aidPrayer, 
                      m.children_courses AS childrenCourses, 
                      m.adult_courses AS adultCourses, 
                      m.ramadan_meal AS ramadanMeal, 
                      m.handicap_accessibility AS handicapAccessibility, 
                      m.ablutions, 
                      m.parking";

        $statuses = implode(',', array_map(function ($v) {
            return "'$v'";
        }, Mosque::ACCESSIBLE_STATUSES));

        if (!empty($lon) && !empty($lat)) {
            $q .= " ,ROUND(get_distance_metres(:lat, :lon, m.latitude, m.longitude) ,0) AS proximity
                            FROM mosque m  
                            INNER JOIN configuration c on m.configuration_id = c.id 
                            WHERE m.status IN ($statuses) AND m.type = 'mosque' 
                            HAVING proximity < 10000 ORDER BY proximity ASC LIMIT 10";

        } elseif ($word) {
            $word = preg_split("/\s+/", trim($word));
            $q .= " FROM mosque m";
            $q .= " INNER JOIN configuration c on m.configuration_id = c.id";
            $q .= " WHERE m.status IN ($statuses) AND m.type = 'mosque'";
            foreach ($word as $key => $keyword) {
                $q .= " AND (m.name LIKE :keyword$key 
                OR m.association_name LIKE :keyword$key 
                OR m.address LIKE :keyword$key 
                OR m.city LIKE :keyword$key 
                OR m.zipcode LIKE :keyword$key)";
            }
        }

        $stmt = $this->em->getConnection()->prepare($q);

        if (!empty($lon) && !empty($lat)) {
            $stmt->bindValue(":lat", $lat);
            $stmt->bindValue(":lon", $lon);
        } elseif ($word) {
            foreach ($word as $key => $keyword) {
                $stmt->bindValue(":keyword$key", "%$keyword%");
            }
        }

        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * @param Mosque $mosque
     *
     * @throws @see MailService
     */
    public function validate(Mosque $mosque)
    {
        $mosque->setStatus(Mosque::STATUS_VALIDATED);
        $this->vichUploadHandler->remove($mosque, 'justificatoryFile');
        $mosque->setJustificatoryFile(null);
        $mosque->setJustificatory(null);
        $this->em->flush();
        $this->mailService->mosqueValidated($mosque);
    }

    /**
     * @param Mosque $mosque
     *
     * @throws @see MailService
     */
    public function check(Mosque $mosque)
    {
        $mosque->setStatus(Mosque::STATUS_CHECK);
        $this->em->flush();
        $this->mailService->checkMosque($mosque);
    }

    /**
     * @param Mosque $mosque
     *
     * @throws @see MailService
     */
    public function duplicated(Mosque $mosque)
    {
        $mosque->setStatus(Mosque::STATUS_DUPLICATED);
        $this->em->flush();
        $this->mailService->duplicatedMosque($mosque);
    }

    /**
     * @param Mosque $mosque
     *
     * @throws @see MailService
     */
    public function rejectScreenPhoto(Mosque $mosque)
    {
        $mosque->setStatus(Mosque::STATUS_VALIDATED);
        $mosque->setImage3(null);
        $this->em->flush();
        $this->mailService->rejectScreenPhoto($mosque);
    }

}
