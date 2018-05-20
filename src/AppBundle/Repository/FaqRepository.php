<?php

namespace AppBundle\Repository;

use Gedmo\Sortable\Entity\Repository\SortableRepository;

class FaqRepository extends SortableRepository
{
    /**
     * @return array
     */
    function getPublicFaq()
    {
        return $this->createQueryBuilder("f")
            ->where("f.enabled = 1")
            ->orderBy("f.position", "ASC")
            ->getQuery()
            ->getResult();
    }

}
