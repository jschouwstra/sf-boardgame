<?php
namespace AppBundle\Repository;

/**
 * Created by PhpStorm.
 * User: Jelle
 */



class SuggestionRepository extends \Doctrine\ORM\EntityRepository
{
    public function findAllOrdered (){
        // test if method is called

//        die("findAllOrdered works!");

        ///////////////////////
        //dql get all games:
        ///////////////////////

        //$dql = 'SELECT game FROM AppBundle:Game game ORDER BY game.name ASC';
        //$query = $this->getEntityManager()->createQuery($dql);


        ///////////////////////
        //Query builder:
        ///////////////////////

        $fields = array('n.name');
        $query = $this->getEntityManager()->createQueryBuilder();

        $query
            ->select($fields)
            ->from('AppBundle:Game','n');

        $results = $query->getQuery()->getResult();


        return $results;


    }
}