<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @return Query
     */
    public function findAllVisibleQuery(BookSearch $search): Query
    {
        $query =  $this->findVisibleQuery();

        if ($search->getMaxYear()) {
            $query = $query
                ->andWhere('b.year <= :maxyear')
                ->setParameter('maxyear', $search->getMaxYear());
        }

        if ($search->getMinYear()) {
            $query = $query
                ->andWhere('b.year >= :minyear')
                ->setParameter('minyear', $search->getMinYear());
        }

        if ($search->getGenres()->count() >0 ) {
            $k = 0;
            foreach ($search->getGenres() as $genre) {
                $k++;
                $query = $query
                    ->andWhere(":genre$k MEMBER OF b.genres")
                    ->setParameter("genre$k", $genre);
            }
        }

           return $query->getQuery();
    }

    /**
     * @return Book[]
     */
    public function findLatest(): array
    {
        return $this->findVisibleQuery()
            ->setMaxResults(4)
            ->getQuery()
            ->getResult();
    }


    private function findVisibleQuery():QueryBuilder
    {
        return $this->createQueryBuilder('b')
            ;
    }
}

// /**
//  * @return Book[] Returns an array of Book objects
//  */
/*
public function findByExampleField($value)
{
    return $this->createQueryBuilder('b')
        ->andWhere('b.exampleField = :val')
        ->setParameter('val', $value)
        ->orderBy('b.id', 'ASC')
        ->setMaxResults(10)
        ->getQuery()
        ->getResult()
    ;
}
*/

/*
public function findOneBySomeField($value): ?Book
{
    return $this->createQueryBuilder('b')
        ->andWhere('b.exampleField = :val')
        ->setParameter('val', $value)
        ->getQuery()
        ->getOneOrNullResult()
    ;
}
*/
