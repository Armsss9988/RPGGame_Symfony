<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Game>
 *
 * @method Game|null find($id, $lockMode = null, $lockVersion = null)
 * @method Game|null findOneBy(array $criteria, array $orderBy = null)
 * @method Game[]    findAll()
 * @method Game[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    public function add(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Game $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
    public function Filter($ram, $diskspace, $categoryID, $sortBy, $orderBy, $gameSearch ,$views): \Doctrine\ORM\Query
    {
        $entityManager = $this->getEntityManager();
        $qb = $entityManager->createQueryBuilder();
        $qb->select('g')
            ->from('App:Game', 'g');
        if (!(is_null($ram) || empty($ram))) {
            $qb->where('g.Ram =' .$ram);
        }

        if (!(is_null($diskspace) || empty($diskspace))) {
            $qb->andWhere('g.DiskSpace <=' .$diskspace);
        }
        if (!(is_null($categoryID) || empty($categoryID)))
        {
            $qb->addSelect('r')
                ->leftJoin('g.gameCategories', 'r')
                ->where('r.Category ='.$categoryID);
        }
        if(!empty($sortBy)) {
            if ($orderBy == 'asc'){
                $qb->addOrderBy('g.Name', 'ASC');
            }
            if ($orderBy == 'desc'){
                $qb->addOrderBy('g.Name', 'desc');
            }
        }
        if(!empty($views)) {
                $qb->addOrderBy('g.Views', 'desc');
        }
        if(!empty($gameSearch)) {
        $qb->andWhere($qb->expr()->orX(
            $qb->expr()->like('g.Name ', ':name')));
        $qb->setParameter(':name', '%'.$gameSearch.'%');
        }
        return $qb->getQuery();

    }

//    /**
//     * @return Game[] Returns an array of Game objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('g.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Game
//    {
//        return $this->createQueryBuilder('g')
//            ->andWhere('g.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
