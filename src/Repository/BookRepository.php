<?php

namespace App\Repository;

use App\Entity\Book;
use App\Entity\BookSearch;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

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

    public function findAllVisibleQuery(User $user, BookSearch $search): Query
    {
        $query = $this->findVisibleQuery($user);

//        if ($search->getMaxYear()) {
//            $query = $query
//                ->andWhere('b.year <= :maxyear')
//                ->setParameter('maxyear', $search->getMaxYear());
//        }
//
//        if ($search->getMinYear()) {
//            $query = $query
//                ->andWhere('b.year >= :minyear')
//                ->setParameter('minyear', $search->getMinYear());
//        }

        if ($search->getGenres()->count() > 0) {
            $k = 0;
            foreach ($search->getGenres() as $genre) {
                $k++;
                $query = $query
                    ->andWhere(":genre$k MEMBER OF b.genres")
                    ->setParameter("genre$k", $genre);
            }
        }

        if ($search->getAuthorlast()) {
            $query->andWhere("b.author_last LIKE '%" . $search->getAuthorlast() . "%'");
        }

        if ($search->getTitle()) {
            $query->andWhere("b.title LIKE '%" . $search->getTitle() . "%'");
        }

        if ($search->getStorage()) {
            $query->andWhere("b.storage LIKE '%" . $search->getStorage() . "%'");
        }

        return $query->getQuery();

    }

    /**
     * @return Book[]
     */
    public function findLatest(): array
    {
        return $this->findVisibleQuery()
            ->setMaxResults(3)
            ->getQuery()
            ->getResult();
    }


    private function findVisibleQuery(User $user): QueryBuilder
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.user = :user')
            ->setParameter('user', $user);
    }


    /**
     * @return mixed
     */
    public function myFindAll()
    {
        return $this->myFindAllBuilder()
            ->getQuery()
            ->getResult();
    }

    public function myFindAllBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder('b')
            ->select('b')
            ->orderBy('b.title', 'ASC');
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
