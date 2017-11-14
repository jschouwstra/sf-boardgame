<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\PlayLog;
use AppBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
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
     * @Route("/show", name="suggestion_show")
     *
     */
    public function getLeastPlayedSuggestion(Request $request)
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

    /**
     * @Route("/test", name="suggestion_test")
     *
     */
    public function getPlayedGames(Request $request)
    {
        $manager = $this->getDoctrine()->getManager();

        /** @var QueryBuilder $qb */
        $qb = $manager->createQueryBuilder();

        $query = $qb
            ->select('g.name, count(p.game) as plays')
            ->from(PlayLog::class, 'p')
            ->leftJoin('p.game', 'g')
            ->groupby('g.name')
            ->having('count(plays) > 0')
            ->getQuery();
        $results = $query->getResult();

        return $this->render('suggestion/show.html.twig', array(
            'suggestionList' => $results
        ));

    }


}
