<?php

namespace AppBundle\Service;

use AppBundle\Entity\Mosque;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Vich\UploaderBundle\Handler\AbstractHandler;

class MosqueService
{

    const ELASTIC_INDEX = "app";
    const ELASTIC_TYPE = "mosque";

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
     * @var Client
     */
    private $elasticClient;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        EntityManagerInterface $em,
        SerializerInterface $serializer,
        AbstractHandler $vichUploadHandler,
        MailService $mailService,
        PrayerTime $prayerTime,
        Client $elasticClient,
        LoggerInterface $logger
    ) {
        $this->em = $em;
        $this->serializer = $serializer;
        $this->vichUploadHandler = $vichUploadHandler;
        $this->mailService = $mailService;
        $this->prayerTime = $prayerTime;
        $this->elasticClient = $elasticClient;
        $this->logger = $logger;
    }

    /**
     * @param $word
     * @param $lat
     * @param $lon
     * @param $page
     *
     * @return mixed
     */
    public function search($word, $lat, $lon, $page)
    {
        if (strlen($word) < 2 && (empty($lat) || empty($lon))) {
            return [];
        }

        if (!empty($word)) {

            $query = [
                "query" => [
                    "multi_match" => [
                        "query" => $word,
                        "fields" => ["name", "associationName", "localisation"],
                        "operator" => "and",
                    ]
                ]
            ];
        }

        if (!empty($lat) && !empty($lon)) {
            $query = [
                'sort' => [
                    '_geo_distance' => [
                        'location' => ["$lat,$lon"]
                    ]
                ]
            ];
        }

        $query["size"] = 10;
        $query["from"] = ($page - 1) * 10;

        try {
            $uri = sprintf("%s/%s/_search", self::ELASTIC_INDEX, self::ELASTIC_TYPE);
            $mosques = $this->elasticClient->get($uri, [
                "json" => $query
            ]);

            $mosques = json_decode($mosques->getBody()->getContents());
        } catch (\Exception $e) {
            $this->logger->error("Elastic: query KO on $uri", [$query, $e->getTrace()]);
            return [];
        }

        $result = [];
        foreach ($mosques->hits->hits as $hit) {
            $mosque = $hit->_source;
            unset($mosque->location);
            if (isset($hit->sort)) {
                $mosque->proximity = (int)$hit->sort[0];
            }
            $result[] = $mosque;
        }

        return $result;
    }

    public function elasticDropIndex()
    {
        try {
            $this->elasticClient->delete(self::ELASTIC_INDEX);
        } catch (\Exception $e) {
            $this->logger->error("Elastic: Can't drop index " . self::ELASTIC_INDEX, [$e->getTrace()]);
        }
    }


    public function elasticCreate(Mosque $mosque)
    {
        if (!$mosque->isElasticIndexable()) {
            return;
        }

        $mosque = $this->serializer->normalize($mosque, 'json', ["groups" => ["elastic"]]);

        $uri = sprintf("%s/%s/%s", self::ELASTIC_INDEX, self::ELASTIC_TYPE, $mosque["id"]);
        $this->elasticClient->post($uri, [
            "json" => $mosque
        ]);

        try {
            $this->elasticClient->post($uri, [
                "json" => $mosque
            ]);
        } catch (\Exception $e) {
            $this->logger->error("Elastic: Can't post on $uri", [$mosque, $e->getTrace()]);
        }
    }

    public function elasticDelete(Mosque $mosque)
    {
        if (!$mosque->isElasticIndexable()) {
            return;
        }

        try {
            $uri = sprintf("%s/%s/%s", self::ELASTIC_INDEX, self::ELASTIC_TYPE, $mosque->getId());
            $this->elasticClient->delete($uri);
        } catch (\Exception $e) {
            $this->logger->error("Elastic: Can't delete $uri", [$e->getTrace()]);
        }
    }

    public function setElasticLocationMapping()
    {
        try {
            $this->elasticClient->put(self::ELASTIC_INDEX, [
                "json" => [
                    "mappings" => [
                        self::ELASTIC_TYPE => [
                            "properties" => [
                                "location" => [
                                    "type" => "geo_point"
                                ]
                            ]

                        ]
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            $this->logger->error("Elastic: set mapping on index " . self::ELASTIC_INDEX, [$e->getTrace()]);
        }
    }

    public function elasticBulkPopulate(\Iterator $mosques)
    {
        $payload = [];

        foreach ($mosques as $mosque) {
            if (!$mosque->isElasticIndexable()) {
                continue;
            }

            $payload[] = json_encode([
                "index" => [
                    "_index" => self::ELASTIC_INDEX,
                    "_type" => self::ELASTIC_TYPE,
                    "_id" => $mosque->getId()
                ]
            ]);
            $payload[] = $this->serializer->serialize($mosque, 'json', ["groups" => ["elastic"]]);
        }

        try {
            $this->elasticClient->post("_bulk", [
                "body" => implode("\n", $payload) . "\n",
                "headers" => ["content-type" => "application/json"],
            ]);
        } catch (\Exception $e) {
            $this->logger->error("Elastic: Can't bulk insert");
        }

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
