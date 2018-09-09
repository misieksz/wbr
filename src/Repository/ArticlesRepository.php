<?php

namespace App\Repository;

use App\Entity\Articles;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class ArticlesRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Articles::class);
    }
    
    public function checkSlug($slug)
    {
        return $this->createQueryBuilder('a')
                ->select('a')
                ->where('a.slug = :slug')
                ->setParameter('slug', $slug)
                ->getQuery()
                ->getResult();
    }
    
 
    public function getQueryBuilder(array $params = array())
    {
       
       
        $qb = $this->createQueryBuilder('a')
                ->select('a, c, u')
                ->leftJoin('a.category', 'c')
                ->leftJoin('a.author', 'u');
        
        if(!empty($params['status']))
        {
            if('published' === $params['status'])
            {
                $qb->where('a.publishedDate <= :currentDate AND a.publishedDate IS NOT NULL')
                        ->setParameter('currentDate', new \DateTime());
            }
            else if('unpublished' === $params['status'])
            {
                $qb->where('a.publishedDate > :currentDate OR a.publishedDate IS NULL')
                        ->setParameter('currentDate', new \DateTime());
            }
        }
        
        if(!empty($params['orderDir']))
        {
            $orderDir = $params['orderDir'];
        }
        
        if(!empty($params['orderBy']))
        {
            $orderDir = (!empty($params['orderDir']) ? $params['orderDir'] : NULL);
            $qb->orderBy('a.publishedDate', $orderDir);
        }
        
        if(!empty($params['categorySlug']))
        {
            $qb->andWhere('c.slug = :categorySlug' )
                    ->setParameter('categorySlug', $params['categorySlug']);
        }
        return $qb->getQuery()->getResult();
          
    } 
    
    public function getStatistics()
    {
        $qb = $this->createQueryBuilder('a')
                ->select('COUNT(a)');
        
        $all = $qb->getQuery()->getSingleScalarResult();
        
        $published = $qb->andWhere('a.publishedDate <= :currentDate AND a.publishedDate IS NOT NULL')
                        ->setParameter('currentDate', new \DateTime())
                        ->getQuery()
                        ->getSingleScalarResult();
        $unpublished = $all - $published;
        
        return array(
            'all' => $all,
            'published' => $published,
            'unpublished' => $unpublished
        );
    }
    
    
    public function newestQuery()
    {
       return $this->createQueryBuilder('a')
               ->select('a, c, u')
               ->leftJoin('a.category', 'c')
               ->leftJoin('a.author', 'u')
               ->where('a.publishedDate <= :currentDate AND a.publishedDate IS NOT NULL')
               ->setParameter('currentDate', new \DateTime())
               ->orderBy('a.publishedDate', 'DESC')
               ->setMaxResults(3)
               ->getQuery()
               ->getResult();
    }

    /*
    public function findBySomething($value)
    {
        return $this->createQueryBuilder('a')
            ->where('a.something = :value')->setParameter('value', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */
}
