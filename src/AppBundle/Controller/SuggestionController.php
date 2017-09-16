<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

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
        foreach($usergames as $usergame){
            $playCount = count($usergame->getPlaylogs()->getValues());
            /** @var Game $usergame */
            $gameName = $usergame->getName();
//            array_push($plays, 1, $gameName);
            $plays[] = array(
                "plays"=>$playCount,
                "name" =>$usergame->getName(),
                "expansions" =>$usergame->getExpansions()
            );
        }
        asort($plays);
        $sliced_array = array_slice($plays, 0, 3);

        $suggestion = $sliced_array;


//        die();
        //Get user's 3 least played games

        return $this->render('suggestion/show.html.twig', array(
//            'games' => $games,
            'suggestionList' => $suggestion
        ));
    }
}
