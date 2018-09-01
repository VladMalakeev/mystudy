<?php

namespace App\Repository;

use App\Entity\Lecturers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Lecturers|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lecturers|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lecturers[]    findAll()
 * @method Lecturers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LecturersRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Lecturers::class);
    }

//    /**
//     * @return Lecturers[] Returns an array of Lecturers objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Lecturers
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
