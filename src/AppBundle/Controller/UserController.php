<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\Game;

use Monolog\Logger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use function var_export;


class UserController extends Controller
{
    /**
     * Adds game(s) to current user.
     *
     * @Route("/user/game/add", name="game_add")
     * @Method({"GET", "POST"})
     */
    public function addGameAction(Request $request)
    {
        /** @var  $form */
        $form = $this->createForm('AppBundle\Form\addGameToUserType');
        $form->handleRequest($request);

        /** Get current User
         * @var User $userObject
         */
        $userObject = $this->getUser();

        /**
         * If form is not submitted get all user's games and set the selectbox accordingly
         */
        if (!$form->isSubmitted()) {
            // games selecteren
            $form["game"]->setData($userObject->getGames());
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /**
             * Get form data as an array
             */
            /** @var  $game */
            $gameArray = $form["game"]->getData();

            /**
             * Unset each game from User Collection and set each game with ArrayCollection "->remove" and "->add"
             * Remove everyhing unselected and add everything selected
             */
            $userObject->removeAllGames();
            foreach ($gameArray as $game) {
                $userObject->addGame($game);
            }

            $em->persist($userObject);
            $em->flush();
            return $this->redirectToRoute('game_index');
        }


        return $this->render('user/addGame.html.twig', array(
            'form' => $form->createView()
        ));
    }



    /**
     * Show current user profile.
     *
     * @Route("user/profile", name="user_profile")
     * @Method({"GET", "POST"})
     */
    public function showUserProfile()
    {
        $gameplayArray = array();

        $user = $this->getUser();
        $userGames = $user->getGames();
        $games = array();

        foreach ($userGames as $game) {
            //games arrays
            array_push($games, $game->getName());
            $gameplays = array();

            /*
             * playlogs array
             * all playlogs of current user AND current game
            */

            $playlogs = $game->getPlaylogs();
            foreach ($playlogs as $playlog) {
                if ($user->getId() == $playlog->getUserId()) {
                    array_push($gameplays, 1);
                }
            }
            array_push($gameplayArray, count($gameplays));
        }

        return $this->render('user/profile.html.twig', array(
            'games' => $games,
            'gameplays' => $gameplayArray,

        ));
    }
}