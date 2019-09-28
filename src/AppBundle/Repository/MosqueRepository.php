<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Mosque;
use AppBundle\Entity\User;
use DateTime;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\QueryBuilder;

/**
 * MosqueRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MosqueRepository extends EntityRepository
{

    const ADMIN_EMAILS = [
        "fb.hp.mawaqit@gmail.com",
        "contact@mawaqit.net"
    ];

    /**
     * @param User $user
     * @param array|null $search
     * @return QueryBuilder
     */
    function search(User $user, $search)
    {
        $qb = $this->createQueryBuilder("m")
            ->leftJoin("m.user", "u", "m.user_id = u.id");

        if (!empty($search)) {
            if (!empty($search["word"])) {
                $qb->where("m.name LIKE :word "
                    . "OR m.associationName LIKE :word "
                    . "OR m.email LIKE :word "
                    . "OR m.address LIKE :word "
                    . "OR m.zipcode LIKE :word "
                    . "OR m.city LIKE :word "
                    . "OR u.username LIKE :word "
                    . "OR u.email LIKE :word"
                )->setParameter(":word", "%" . trim($search["word"]) . "%");
            }


            if (!empty($search["id"])) {
                $qb->andWhere("m.id = :id")
                    ->setParameter(":id", trim($search["id"]));
            }

            if (!empty($search["status"])) {
                $qb->andWhere("m.status = :status")
                    ->setParameter(":status", $search["status"]);
            }

            if (!empty($search["sourceCalcul"])) {
                $qb->innerJoin("m.configuration", "c")
                    ->andWhere("c.sourceCalcul = :sourceCalcul")
                    ->setParameter(":sourceCalcul", $search["sourceCalcul"]);
            }

            if (!empty($search["type"]) && $search["type"] !== 'ALL') {
                $qb->andWhere("m.type = :type")
                    ->setParameter(":type", $search["type"]);
            }

            if (!empty($search["department"])) {
                $qb->andWhere("m.zipcode LIKE :zipcode")
                    ->setParameter(":zipcode", trim($search["department"]) . "%");
            }

            if (!empty($search["country"])) {
                $qb->andWhere("m.country = :country")
                    ->setParameter(":country", $search["country"]);
            }

            if (!empty($search["city"])) {
                $qb->andWhere("m.city = :city")
                    ->setParameter(":city", $search["city"]);
            }
        }

        if (!empty($search["userId"])) {
            $qb->andWhere("m.user = :userId")
                ->setParameter(":userId", $search["userId"]);
        }

        // By default not show homes for admin user
        if (empty($search["userId"]) && in_array($user->getEmail(), self::ADMIN_EMAILS) && empty($search["type"])) {
            $qb->andWhere("m.type = :type")
                ->setParameter(":type", "mosque");
        }

        if (!$user->isAdmin()) {
            $qb->andWhere("u.id = :userId")
                ->setParameter(":userId", $user->getId());
        }

        $qb->orderBy("m.created", "DESC");

        return $qb;
    }


    private function getValidatedMosqueQb()
    {
        return $this->createQueryBuilder("m")
            ->where("m.type = 'mosque'")
            ->andWhere("m.status = :status")
            ->setParameter(':status', Mosque::STATUS_VALIDATED);
    }


    /**
     * @param string $query
     * @return QueryBuilder
     */
    function publicSearch($query)
    {
        if (!empty($query)) {
            $query = preg_split("/\s+/", trim($query));
            $qb = $this->getValidatedMosqueQb();
            foreach ($query as $key => $keyword) {
                $qb->andwhere("m.name LIKE :keyword$key "
                    . "OR m.address LIKE :keyword$key "
                    . "OR m.city LIKE :keyword$key "
                    . "OR m.zipcode LIKE :keyword$key "
                )->setParameter(":keyword$key", "%$keyword%");
            }
            return $qb;
        }
    }

    /**
     * @param $query
     * @return mixed
     */
    function searchMosquesWithCalendar($query)
    {
        if (!empty($query)) {
            $qb = $this->publicSearch($query)
                ->innerJoin("m.configuration", "c", "m.configuration_id = c.id")
                ->select("m.id, CONCAT(m.name, ' - ', m.city, ' ', m.countryFullName) AS label")
                ->orderBy("m.name", "ASC");

            return $qb->getQuery()->getResult();
        }

        return [];
    }

    /**
     * get configured mosques with minimum one image set (image1)
     * @return array
     */
    function getMosquesWithImageQb()
    {
        return $this->getValidatedMosqueQb()
            ->andWhere("m.image1 IS NOT NULL")
            ->orderBy("m.id", "DESC");
    }

    /**
     * set updated to now for all mosques
     */
    function forceUpdateAll()
    {
        $qb = $this->createQueryBuilder("m")
            ->update()
            ->set("m.updated", ":date")
            ->setParameter(":date", new DateTime());
        $qb->getQuery()->execute();
    }

    /**
     * @return mixed
     * @throws NonUniqueResultException
     */
    function getCount()
    {
        return $this->createQueryBuilder("m")
            ->select("count(m.id)")
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @return mixed
     * @throws NonUniqueResultException
     */
    function countMosques()
    {
        return $this->getValidatedMosqueQb()
            ->select("count(m.id)")
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * get mosques information for google map
     * @return array
     */
    function getAllMosquesForMap()
    {
        return $this->getValidatedMosqueQb()
            ->select("m.slug, m.name, m.address, m.city, m.zipcode, m.countryFullName,  m.longitude as lng, m.latitude as lat")
            ->andWhere("m.addOnMap = 1")
            ->andWhere("m.type = 'mosque'")
            ->andWhere("m.latitude is not null")
            ->andWhere("m.longitude is not null")
            ->getQuery()
            ->getArrayResult();
    }


    /**
     * get mosques by country
     * @return array
     */
    function getNumberByCountry()
    {
        return $this->createQueryBuilder("m")
            ->select("count(m.id) as nb, m.country")
            ->where("m.status = :status")
            ->orderBy("nb", "DESC")
            ->groupBy("m.country")
            ->getQuery()
            ->setParameter(':status', Mosque::STATUS_VALIDATED)
            ->getResult();
    }

    /**
     * get mosques grouped by status
     * @return array
     */
    function getNumberByStatus()
    {
        return $this->createQueryBuilder("m")
            ->select("count(m.id) as nb, m.status")
            ->groupBy("m.status")
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array
     */
    function getCitiesByCountry($country)
    {
        $cities = $this->createQueryBuilder("m")
            ->select("UPPER(m.city) as city")
            ->distinct("m.city")
            ->where("m.country = :country")
            ->andWhere("m.type = :type")
            ->orderBy('m.city', 'ASC')
            ->setParameter(':country', $country)
            ->setParameter(':type', Mosque::TYPE_MOSQUE)
            ->getQuery()
            ->getScalarResult();

        return array_column($cities, 'city');
    }

    /**
     * Remove not validated mosques if no response after 15 days
     * @return integer
     */
    function removeNotValidated()
    {
        return $this->createQueryBuilder("m")
            ->delete()
            ->where("m.status NOT IN (:status)")
            ->andWhere("m.updated < :date")
            ->setParameter(":status", [Mosque::STATUS_VALIDATED, Mosque::STATUS_SUSPENDED])
            ->setParameter(":date", new DateTime("-30 day "))
            ->getQuery()
            ->execute();
    }

    /**
     * Get mosques without screen photo to remind them
     * @return mixed
     * @throws \Exception
     */

    function getMosquesWithoutScreenPhoto()
    {
        return $this->getValidatedMosqueQb()
            ->andWhere("m.image3 IS NULL")
            ->andWhere("m.created > :date")
            ->andWhere("m.emailScreenPhotoReminder IS NULL OR m.emailScreenPhotoReminder <= 3")
            ->setParameter(":date", new DateTime("2019-09-27"))
            ->getQuery()
            ->execute();
    }
}
