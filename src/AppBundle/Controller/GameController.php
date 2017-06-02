<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Game;
use AppBundle\Entity\PlayLog;
use AppBundle\Entity\User;
use AppBundle\Form\GameType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\HttpFoundation\Request;
/**
 * Game controller.
 *
 * @Route("game")
 */
class GameController extends Controller
{
    /**
     * Lists all game entities.
     *
     * @Route("/", name="game_index")
     * @Method("GET")
     */
    public function indexAction(Request $request)
    {
        //get user_id

        $usr = $this->getUser();
        $id = $usr->getId();

        $em = $this->getDoctrine()->getManager();
//        $dql="SELECT g FROM AppBundle\Entity\Game g Join g.users u WHERE u.id := user_id";
        $dql='SELECT g FROM Game ';
        $query = $em->createQuery($dql);
        $query->setParameter('user_id', $id);
        $current_games = $query->getResult();

        /*
         * @var $paginator \Knp\Component\Pager\Paginator
         */
//        $paginator = $this->get('knp_paginator');
//        $result = $paginator->paginate(
//             $query,
//            $request->query->getInt('page', 1),
//            $request->query->getInt('limit', 25)
//        );
//        dump(get_class($paginator));

        return $this->render('game/index.html.twig', array(
            'games' => $current_games,
            'max_limit_error' => 25
        ));
    }

    /**
     * Creates a new game entity.
     *
     * @Route("/new", name="game_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request)
    {

        $game = new Game();

        $form = $this->createForm('AppBundle\Form\GameType', $game);
        $form->handleRequest($request);

        $game->setUser($this->getUser());
        if ($form->isSubmitted() && $form->isValid()) {
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
        $deleteForm = $this->createDeleteForm($game);


        return $this->render('game/show.html.twig', array(
            'game' => $game,
            'delete_form' => $deleteForm->createView(),
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
     * Displays a form to edit an existing game entity.
     *
     * @Route("/{id}/log", name="game_log")
     * @Method({"GET", "POST"})
     */
    public function addLogAction(Request $request, Game $game)
    {
        $playlog = new PlayLog();
        $form = $this->createForm(GameType::class, $game);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            //Save playLog
            $em = $this->getDoctrine()->getManager();
            $em->persist($playlog);
            $em->flush();

        }
        // Render / return view incl. formulier.
        return $this->render('game/log.html.twig', array(
            'game' => $game,
            'form' => $form->createView(),
        ));
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
            ->getForm()
        ;
    }
}
