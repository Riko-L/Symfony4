<?php

namespace App\Repository;

use App\Entity\Ads;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Ads|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ads|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ads[]    findAll()
 * @method Ads[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdsRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Ads::class);
    }

//    /**
//     * @return Ads[] Returns an array of Ads objects
//     */

    public function findByCategoryRegionAndKeywrod($value)
    {
        if($value['search'] == ""){

            $value['search'] = null;
        }
        if($value['category'] == ""){

            $value['category'] = null;
        }
        if($value['region'] == ""){

            $value['region'] = null;
        }



        $qb = $this->createQueryBuilder('a')
            ->join('a.category' , 'cat')
            ->join('a.region' , 'reg')
            ->select('a');

        if ($value['category']){
            $qb->andWhere('cat.id = :catName')
                ->setParameter('catName' , $value['category'] );
        }

        if($value['region']){
            $qb->andWhere('reg.id = :regName');
            $qb->setParameter('regName' , $value['region'] );
        }

        if($value['search']){
            $qb->andWhere('a.title LIKE :keyWord');
            $qb->setParameter('keyWord' , '%'.$value['search'].'%' );

        }

        return  $qb->andWhere('a.isActive = true')->getQuery()->getResult();


    }

// SELECT * From ads JOIN category as cat ON ads.category_id = cat.id WHERE cat.name = "Vente";
//SELECT * From ads JOIN category as cat   ON ads.category_id = cat.id  JOIN region as reg ON ads.region_id = reg.id WHERE cat.name = "Vente";


    /*
    public function findOneBySomeField($value): ?Ads
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
