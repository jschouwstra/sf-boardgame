<?php

namespace AppBundle\Controller;

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
        //Use existing GameRepository, Appbundle:Game
        $suggestionRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Suggestion');
        $suggestion = $suggestionRepository->findLeastPlayedGames();

        //SuggestionRepository

        //Get user's 3 least played games

        return $this->render('suggestion/show.html.twig', array(
            'games' => $games
        ));
    }
}
