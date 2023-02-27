<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Serie>
 *
 * @method Serie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Serie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Serie[]    findAll()
 * @method Serie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SerieRepository extends ServiceEntityRepository
{
    const SERIE_LIMIT = 50;
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Serie::class);
    }

    public function save(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Serie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }


    public function findBestSeries(int $page){


        //page 1 -> 0 à 49
        // page 2 50 à 99

        $offset = ($page - 1 ) * self::SERIE_LIMIT;

       //en querybuilder
        $qb = $this->createQueryBuilder('s');
        //jointure sur les attributs d'instance
        $qb->leftJoin("s.seasons", "sea")
            //récupération des colonnes de la jointure
            ->addSelect("sea")
            ->addOrderBy('s.popularity', 'DESC')
           // ->andWhere('s.vote > 8')
           // ->andWhere('s.popularity > 100')
           ->setFirstResult($offset)
            ->setMaxResults(self::SERIE_LIMIT);

        $query = $qb->getQuery();
        //permet de gérer les offsets avec jointure
        $paginator = new Paginator($query);

        return $paginator;

    }


//    /**
//     * @return Serie[] Returns an array of Serie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Serie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
