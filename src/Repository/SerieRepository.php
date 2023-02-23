<?php

namespace App\Repository;

use App\Entity\Serie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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


    public function findBestSeries(){

        //en DQL
        //récupération des séries avec un vote > 8 et une popularit > 100 ordonné par popularité

       // $dql = "SELECT s FROM App\Entity\Serie AS s
        //        WHERE s.vote > 8
        //        AND  s.popularity > 100
       //         ORDER BY s.popularity DESC";
        //transforme la chaîne de caractères $dql en objet
       // $query = $this->getEntityManager()->createQuery($dql);
        //ajoute une limite de résultat
       // $query->setMaxResults(50);

       // return $query->getResult();


       //en querybuilder
        $qb = $this->createQueryBuilder('s');
        $qb->addOrderBy('s.popularity', 'DESC')
            ->andWhere('s.vote > 8')
            ->andWhere('s.popularity > 100')
            ->setMaxResults(50);

        $query = $qb->getQuery();

        return $query->getResult();

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
