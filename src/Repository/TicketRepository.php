<?php

namespace App\Repository;

use App\Entity\Ticket;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Ticket|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ticket|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ticket[]    findAll()
 * @method Ticket[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TicketRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ticket::class);
    }

    public function tickets($user)
    {
        $entityManager = $this->getEntityManager();
        $statement = $entityManager->getConnection()->prepare(
            'SELECT t.id AS ID_Ticket, u.id AS ID_User
            FROM ticket AS t
            INNER JOIN user  AS u
            ON t.user_id = u.id
            WHERE u.id = :val
            ORDER BY t.id ASC');
        $statement->execute(['val' => $user->getId()]);
        // returns an array of User objects
        return $statement->fetchAll();
    }
}
