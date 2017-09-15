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
        $normalizers = new \Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer();

        $usergames = $user->getGames();
        $plays = [];
        foreach($usergames as $game){

            //push to array:
            // array(
            //  count($game->getPlayLogs),
            //  $game-> getId()
            //)
            //Get game with least plays
            // return Game with least plays
            $playCount = count($game->getPlaylogs()->getValues());

            /** @var Game $game */
            array_push($plays, $playCount );
        }
//        asort($plays);
//        array_slice($plays, 1, 1, true);
        foreach($plays as $play){
            echo $play."<br>";
        }
        die();
        //Get user's 3 least played games

        return $this->render('suggestion/show.html.twig', array(
//            'games' => $games,
            'suggestion' => $suggestion
        ));
    }
}
