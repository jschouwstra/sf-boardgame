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
        $emailform = $this->createForm('AppBundle\Form\requestGame');
        $form = $this->createForm('AppBundle\Form\addGameToUserType');

        $form->handleRequest($request);
        $emailform->handleRequest($request);

        /** Get current User
         * @var User $userObject
         */
        $userObject = $this->getUser();

        //Add game
        //If form is not submitted get all user's games and set the selectbox accordingly
        if (!$form->isSubmitted()) {
            // games selecteren
            $form["game"]->setData($userObject->getGames());
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            //get form data as array
            /** @var  $game */
            $gameArray = $form["game"]->getData();

            /**
             * Unset each game from User Collection and set each game with ArrayCollection "->remove" and "->add"
             * Remove every unselected and add everything selected to the User object
             */
            $userObject->removeAllGames();
            foreach ($gameArray as $game) {
                $userObject->addGame($game);
            }

            $em->persist($userObject);
            $em->flush();
            return $this->redirectToRoute('game_index');
        }

        //send mail
        if ($emailform->isSubmitted() && $emailform->isValid()) {
            $feedbackType = $request->request->get('feedbackType');
            $subject = "";
            if ($feedbackType == 'requestGame') {
                $subject = "New game request";
            }
            /** @var User $usr */
            $usr = $this->getUser();
            $feedback = $emailform->getData('feedback');
            $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($usr->getUsername() . '@example.com')
                ->setTo('admin@example.com')
                ->setBody(//Get template for email
                    $this->renderView(
                        'email/ForAdmin.html.twig',
                        array(
                            'subject' => $subject,
                            'username' => $usr->getUsername(),
                            'user_id' => $usr->getId(),
                            'feedbackType' => $feedbackType,
                            'email' => $usr->getEmail(),
                            'feedback' => $feedback,
                        )
                    )
                    , 'text/html');
            if ($feedback !== null) {

            }
            $this->get('mailer')->send($message);

        }
        return $this->render('user/addGame.html.twig', array(
            'form' => $form->createView(),
            'emailform' => $emailform->createView()

        ));
    }

    public function getUserGamesForGraph()
    {
        $games = array();
        $gameplayArray = array();

        $totalGames = array();
        $totalGameplays = array();

        $user = $this->getUser();
        $userGames = $user->getGames();

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

        for ($x = 0; $x < count($gameplayArray); $x++) {

            if ($gameplayArray[$x] != null) {
                array_push($totalGameplays, $gameplayArray[$x]);
                array_push($totalGames, $games[$x]);

            }
        }
        return array($totalGames, $totalGameplays);
    }

    /**
     * Show current user's dashboard.
     *
     * @Route("user/home", name="user_dashboard")
     * @Method({"GET", "POST"})
     */
    public function showDashboard()
    {
//        $userGames = $this->getUserGamesForGraph();
//        $totalGames = $userGames[0];
//        $totalGameplays = $userGames[1];
        return $this->render('user/dashboard.html.twig', array(
//            'games' => $totalGames,
//            'gameplays' => $totalGameplays,
        ));
    }

    /**
     *
     * @Route("/user/graphs", name="user_graphs")
     *
     */
    public function showGraphsAction()
    {
        $userGames = $this->getUserGamesForGraph();
        $totalGames = $userGames[0];
        $totalGameplays = $userGames[1];
        return $this->render('user/graphs.html.twig', array(
            'games' => $totalGames,
            'gameplays' => $totalGameplays,
        ));
    }


}