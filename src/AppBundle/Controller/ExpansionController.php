<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Expansion controller.
 *
 * @Route("expansion")
 */
class ExpansionController extends Controller
{

    /**
     * @Route("/add/to/game/{gameId}", name="add_expansion_to_game")
     * @Method("GET")
     */
    public function addToGameAction(Game $gameId, Request $request)
    {
        $game = $gameId;
        $form = $this->createForm('AppBundle\Form\addExpansionToGameType');

        if (!$form->isSubmitted()) {
            // games selecteren
            $form["expansion"]->setData($game->getExpansions());
        }

        if($form->isValid()){
            $form->handleRequest();

        }

        return $this->render('expansion/addToGame.html.twig', array(
        'game' => $game,
        'form' => $form->createView()
        ));
    }


}