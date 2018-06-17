<?php

namespace AppBundle\Service;

use AppBundle\Entity\Mosque;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;

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

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
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
     * @param $word
     * @return array
     */
    public function searchApi($word)
    {
        if (strlen($word) > 1) {
            $mosques = $this->em->getRepository("AppBundle:Mosque")
                ->publicSearch($word)
                ->getQuery()
                ->getResult();

            return $this->serializer->normalize($mosques, 'json', ["groups" => ["search"]]);
        }
        return [];
    }
}
