<?php

namespace AppBundle\Service;

use AppBundle\Entity\Mosque;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Query;
use FOS\ElasticaBundle\Finder\PaginatedFinderInterface;
use Pagerfanta\Exception\OutOfRangeCurrentPageException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
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

    /**
     * @var PrayerTime
     */
    private $prayerTime;

    /**
     * @var PrayerTime
     */
    private $finder;

    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        AbstractHandler $vichUploadHandler,
        MailService $mailService,
        PrayerTime $prayerTime,
        PaginatedFinderInterface $finder
    ) {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->vichUploadHandler = $vichUploadHandler;
        $this->mailService = $mailService;
        $this->prayerTime = $prayerTime;
        $this->finder = $finder;
    }

    /**
     * @param $word
     * @param $lat
     * @param $lon
     * @param $page
     *
     * @return mixed
     */
    public function searchApi($word, $lat, $lon, $page)
    {
        if (strlen($word) < 3 && (empty($lat) || empty($lon))) {
            return [];
        }

        $query = new Query();

        if (!empty($word)) {
            $query->setRawQuery([
                "query" => [
                    "query_string" => [
                        "query" => "*$word*"
                    ]
                ]
            ]);
        }

        if (!empty($lat) && !empty($lon)) {
            $query->setRawQuery([
                'sort' => [
                    '_geo_distance' => [
                        'location' => [
                            'lat' => $lat,
                            'lon' => $lon
                        ],
                        'order' => 'asc',
                        'distance_type' => 'arc',
                        'ignore_unmapped' => true
                    ]
                ]
            ]);
        }

        $mosques = $this->finder->findPaginated($query);

        try {
            $mosques->setCurrentPage($page);
        } catch (OutOfRangeCurrentPageException $e) {
            throw new NotFoundHttpException();
        }

        return $mosques;
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
        $this->vichUploadHandler->remove($mosque, 'file3');
        $mosque->setImage3(null);
        $mosque->setFile3(null);
        $this->em->flush();
        $this->mailService->rejectScreenPhoto($mosque);
    }

}
