<?php

namespace App\Repository;

use App\Entity\User;
use App\Entity\Article;
use Doctrine\ORM\Query;
use App\Entity\ArticleSearch;
use Doctrine\ORM\QueryBuilder;
use App\Entity\ArticleSearchUser;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }
    
    /**
     * findAllVisible
     *
     * @return Query[]
     */
    public function findAllVisibleQuery(ArticleSearch $search): Query
    {
        $query =  $this->findQuery();

        if($search->getInfo()){
            
            $query = $query
                        ->andWhere('p.reference LIKE :info')
                        ->setParameter('info', '%'.$search->getInfo().'%');
        }

        if($search->getInfoCatalog()){
            $query = $query
                        ->andWhere('p.catalog = :id')
                        ->setParameter('id', $search->getInfoCatalog());
            
        }

        return $query->getQuery();  
    }

    /**
     * findArticleSearchUser
     *
     * @return Query[]
     */
    public function findArticleSearchUser(ArticleSearchUser $search, $id): Query
    {
        $query =  $this->findQueryByUser($id);

        if($search->getInfo()){
            
            $query = $query
                        ->andWhere('p.reference LIKE :info')
                        ->setParameter('info', '%'.$search->getInfo().'%');
                        
        }

        return $query->getQuery();  
    }



    /**
     * findLatest
     *
     * @return Article[]
     */
    public function findLatest(): array
    {
        return $this->findQuery()
                    ->orderBy('p.id', 'DESC')
                    ->setMaxResults(8)
                    ->getQuery()
                    ->getResult();
    }


    private function findQuery(): QueryBuilder
    {
        return $this->createQueryBuilder('p');
    }

    private function findQueryByUser($id): QueryBuilder
    {
        return $this->createQueryBuilder('p')
                    ->andWhere('p.createdUser = :id')
                    ->setParameter('id', $id);;
    }

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Article
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
