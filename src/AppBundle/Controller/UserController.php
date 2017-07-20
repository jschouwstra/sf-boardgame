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
     * @Route("user/game/add", name="game_add")
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
     * @Route("user/profile", name="game_add")
     * @Method({"GET", "POST"})
     */
    public function showUserProfile(){

        /** @var  $usr */
        $usr = $this->getUser();

        /** @var  $userGames */
        $userGames = $usr->getGames();

        $games = array();
        $gameplays = array();

        /** @var  $game */
        foreach($userGames as $game){
            array_push( $games, $game->getName() );
//            array_push( $playlogs, $game->getPlaylogs($game->getId()) );
        }

        foreach($userGames as $game){
            $numberOfPlays = count( $game->getPlaylogs() );
            array_push( $gameplays, $numberOfPlays );
        }


        return $this->render('user/profile.html.twig', array(
            'games' => $games,
            'gamePLaysCount' => $gameplays

        ));
    }
}