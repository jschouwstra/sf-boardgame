<?php

namespace AppBundle\Controller;

use AppBundle\AppBundle;
use AppBundle\Entity\Game;
use AppBundle\Entity\PlayLog;
use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use FOS\UserBundle\FOSUserBundle;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\QueryBuilder;


/**
 * @Route("/suggestion")
 */
class SuggestionController extends Controller
{

    public function indexAction($name)
    {
        return $this->render('', array('name' => $name));
    }

    /**
     * @Route("/test", name="suggestion_test")
     *
     */
    public function suggestionShow()
    {

            $leastPlayedGames = $this->getLeastPlayedGames();

        return $this->render('suggestion/show.html.twig', array(
            'leastPlayedGames' => $leastPlayedGames,
            'leastPlayedGames' => $leastPlayedGames
        ));

    }

    /**
     * @Route("/show", name="suggestion_show")
     *
     */
    public function getLeastPlayedSuggestion()
    {
        /** @var User $user */
        $user = $this->getUser();

        $usergames = $user->getGames();
        $plays = array();
        foreach ($usergames as $usergame) {
            $playCount = count($usergame->getPlaylogs()->getValues());
            /** @var Game $usergame */
            $gameName = $usergame->getName();
//            array_push($plays, 1, $gameName);
            $plays[] = array(
                "plays" => $playCount,
                "name" => $usergame->getName(),
                "expansions" => $usergame->getExpansions()
            );
        }
        //Reverse array order:
        asort($plays);

        //Show only 3 array elements:
        $sliced_array = array_slice($plays, 0, 3);


        $suggestion = $sliced_array;

        return $this->render('suggestion/show.html.twig', array(
            'suggestionList' => $suggestion
        ));
    }


    public function getLeastPlayedGames()
    {
        /** @var User $user */
        $user = $this->getUser();
        $userID = $user->getId();
        $manager = $this->getDoctrine()->getManager();
        /** @var QueryBuilder $qb */
        $qb = $manager->createQueryBuilder();

        $query = $qb
            ->select('g.name, count(p.game) as plays')
            ->from(PlayLog::class, 'p')
            ->from(User::class, 'u')
            ->where('u.id ='.$userID)
            ->leftJoin('p.game', 'g')
            ->groupby('g.name', 'p.user_id')
            ->having('count(plays) > 0')
            ->orderBy('plays', 'asc')
            ->setMaxResults(5)
            ->getQuery();

        $results = $query->getResult();

        return $results;
    }
    public function getMostPlayedGames()
    {
        /** @var User $user */
        $user = $this->getUser();
        $userID = $user->getId();
        $manager = $this->getDoctrine()->getManager();
        /** @var QueryBuilder $qb */
        $qb = $manager->createQueryBuilder();

        $query = $qb
            ->select('g.name, count(p.game) as plays')
            ->
            ->from(PlayLog::class, 'p')
            ->from(User::class, 'u')
            ->where('u.id ='.$userID)
            ->leftJoin('p.game', 'g')
            ->groupby('g.name', 'p.user_id')
            ->having('count(plays) > 0')
            ->orderBy('plays', 'asc')
            ->setMaxResults(5)
            ->getQuery();

        $results = $query->getResult();

        return $results;
    }


}
