<?php

namespace AppBundle\Service;

use Doctrine\ORM\EntityManagerInterface;

class MosqueService
{

    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getCalendarList()
    {
        $res = $this->em
            ->getRepository("AppBundle:Configuration")
            ->createQueryBuilder("c")
            ->select("c.id, m.zipcode, m.city, m.country")
            ->innerJoin("c.mosque", "m", "c.mosque_id = m.id")
            ->where("c.sourceCalcul = 'calendar'")
            ->andWhere("c.calendar IS NOT NULL")
            ->andWhere("m.city IS NOT NULL AND m.city != ''")
            ->groupBy("m.country, m.city")
            ->orderBy("m.country, m.zipcode", "ASC")
            ->getQuery()
            ->execute();

        $calendars = [];
        foreach ($res as $item) {
            $calendars[strtoupper($item["country"])][] = [
                'id' => $item["id"],
                'label' => substr($item["zipcode"], 0, 2) . ' ' . ucfirst($item["city"]) . ' ' . $item["zipcode"],
            ];
        }

        return $calendars;
    }

}
