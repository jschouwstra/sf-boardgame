<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use AppBundle\Entity\Expansion;

use Doctrine\DBAL\Connection;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Nataniel\BoardGameGeek\Thing;
use function var_dump;


/**
 * Game controller.
 *
 * @Route("game")
 */
class GameController extends Controller
{
    /**
     * Lists all user's game entities.
     *
     * @Route("/", name="game_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        /*
         * The FOSUser object (current user) is injected in the container so we can access it globally
         *
        */
        /** @var User $usr */
        $usr = $this->getUser();
        $userGames = $usr->getGames();

        return $this->render('game/index.html.twig', array(
            'games' => $userGames,
            'max_limit_error' => 25
        ));
    }


    /**
     * Lists all user's game entities as JSON.
     *
     * @Route("/user/json", name="user_games_json")
     * @Method("GET")
     */
    public function returnUserGamesAsJson(Request $request)
    {
        /*
         * The FOSUser object (current user) is injected in the container so we can access it globally
         *
        */

        /** @var User $usr */
        $usr = $this->getUser();
        $userGames = $usr->getGames();

        $games = array();
        foreach ($userGames as $game) {
            array_push($games, $game->getName());
        }
        $serializer = $this->get('jms_serializer');

        $jsonContent = $serializer->serialize($games, 'json');
        return new Response(
            $jsonContent
        );
    }

    /**
     * Lists all game entities as JSON.
     *
     * @Route("/all/json", name="find_games_json")
     * @Method("GET")
     */
    public function returnAllGamesAsJson(Request $request)
    {
        //Use existing GameRepository, Appbundle:Game
        $gameRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository('AppBundle:Game');
        $name = $request->get('name');
        $games = $gameRepository->findByName($name);

        $serializer = $this->get('jms_serializer');

        $jsonContent = $serializer->serialize($games, 'json');
        return new Response(
            $jsonContent
        );
    }

    /**
     * Creates a new game entity.
     *
     * @Route("/new", name="game_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {
        $client = new \Nataniel\BoardGameGeek\Client();
        $game = new Game();
        $form = $this->createForm('AppBundle\Form\GameType', $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bgg_id = $form->get('bgg_id')->getData();
            $thing = $client->getThing($bgg_id, true);
            $game->setName($thing->getName());

            $em = $this->getDoctrine()->getManager();
            $em->persist($game);
            $em->flush($game);
            return $this->redirectToRoute('game_show', array('id' => $game->getId()));
        }

        return $this->render('game/new.html.twig', array(
            'game' => $game,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a game entity.
     *
     * @Route("/{id}", name="game_show")
     * @Method("GET")
     */
    public function showAction(Game $game)
    {
        $client = new \Nataniel\BoardGameGeek\Client();
        $thing = $client->getThing($game->getBggId(), true);


        return $this->render('game/show.html.twig', array(
            //bgg game properties:
            'image' => $thing->getImage(),
            'playingTime' => $thing->getPlayingTime(),
            'minPlayers' => $thing->getMinPlayers(),
            'maxPlayers' => $thing->getMaxPlayers(),
            'publishedBy' => $thing->getBoardgamePublishers(),

            'game' => $game,
        ));
    }

    /**
     * Displays a form to edit an existing game entity.
     *
     * @Route("/{id}/edit", name="game_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Game $game)
    {
        $deleteForm = $this->createDeleteForm($game);
        $editForm = $this->createForm('AppBundle\Form\GameType', $game);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('game_show', array('id' => $game->getId()));
        }

        return $this->render('game/edit.html.twig', array(
            'game' => $game,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Removes a game entity from User collection.
     *
     * @Route("/remove/game/user", name="remove_user_game")
     */
    public function removeGameFromUser(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $gameId = json_decode($request->getContent());
        $game = $em->getRepository(Game::class)->find($gameId);
        $user = $this->getUser();

        /** @var User $user */
        $user->removeGame($game);
        $em->persist($user);
        $em->flush();

        return new Response("Game remove from User collection: success");

    }

    /**
     * Deletes a game entity.
     *
     * @Route("/{id}", name="game_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Game $game)
    {
        $form = $this->createDeleteForm($game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($game);
            $em->flush($game);
        }

        return $this->redirectToRoute('game_index');
    }

    /**
     * Creates a form to delete a game entity.
     *
     * @param Game $game The game entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Game $game)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('game_delete', array('id' => $game->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @Route("/add/expansion/to/{gameId}", name="add_expansion_to_game")
     * @Method({"GET", "POST"})
     */
    public function addExpansionAction(Game $gameId, Request $request)
    {
        $form = $this->createForm('AppBundle\Form\addExpansionToGameType');
        $form->handleRequest($request);
        $game = $gameId;

        if (!$form->isSubmitted()) {
            //if form not submitted set current known data
            $form["expansion"]->setData($game->getExpansions());
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $expansionArray = $form["expansion"]->getData();
            $game->removeAllExpansions();
            foreach ($expansionArray as $expansion) {
                $game->addExpansion($expansion);
            }

            $em->persist($game);
            $em->flush();
            return $this->redirectToRoute('game_index');


        }

        return $this->render('game/addExpansion.html.twig', array(
            'game' => $game,
            'form' => $form->createView()
        ));
    }
}
