<?php

namespace AppBundle\Repository;
use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;

/**
 * GameRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class GameRepository extends EntityRepository
{
    public function findAllOrdered ($user_id){
        // test if method is called

        echo $user_id;
//        die("findAllOrdered works!");

        ///////////////////////
        //dql get all games:
        ///////////////////////

//        $dql = 'SELECT game FROM AppBundle:Game game ORDER BY game.name ASC';
//        $query = $this->getEntityManager()->createQuery($dql);

        ///////////////////////
        //Query builder:
        ///////////////////////

        $qb = $this->createQueryBuilder('game')
            ->addOrderBy('game.name', 'ASC')
            ->andWhere('game.users.user_id = :user')

            ->setParameter('user', $user_id);
        ;

        $query = $qb->getQuery();
        return $query->execute();


    }

    public function getUserGames($user_id){
        $em = $this->getEntityManager();
        $query = $em->createQuery('SELECT g '
            . 'FROM AppBundle:Game g '
            . 'JOIN g.users_games ug '
            . 'JOIN ug.user u '
            . 'WHERE u.id = :id ')
            ->setParameter('user', $user_id);

        return $query->getResult();
    }

    public function findGameByOwner( $user ) {
        $qb = $em->getRepository( GameEntity::class )->createQueryBuilder( 'game_t' );
        $qb->join( 'game_t.user', 'user_t' )
            ->where( 'user_t.id = :userId' )
            ->setParameter( 'userId', $user->getId() );
        return $qb->getQuery()->getResult();
    }

}
