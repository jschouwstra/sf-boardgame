<?php

namespace AppBundle\Controller;

use AppBundle\Entity\PlayLog;
use AppBundle\Entity\Game;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * Playlog controller.
 *
 * @Route("playlog")
 */
class PlayLogController extends Controller
{
    /**
     * Lists all playLog entities.
     *
     * @Route("/", name="playlog_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $playLogs = $em->getRepository('AppBundle:PlayLog')->findAll();

        return $this->render('playlog/index.html.twig', array(
            'playLogs' => $playLogs,
        ));
    }
    /**
     * Creates a new playLog entity.
     *
     * @Route("/{gameId}/new", name="playlog_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, $gameId)
    {
        $playlog = new PlayLog();
        $em = $this->getDoctrine()->getManager();
        $game = $em ->getRepository(Game::class)->find($gameId);
        $playlog->setGame($game);
        $form = $this->createForm('AppBundle\Form\PlayLogType', $playlog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /* @var $playLog PlayLog */
            $playlog = $form->getData();
           // echo $playlog->getGame()->getId() .'!';

            $em->persist($playlog);
            $em->flush();
        }

        return $this->render('playlog/new.html.twig', array(
            'playLog' => $playlog,
            'form' => $form->createView(),
        ));
    }
    /**
     * Finds and displays a playLog entity.
     *
     * @Route("/{id}", name="playlog_show")
     * @Method("GET")
     */
    public function showAction(PlayLog $playLog)
    {
        $deleteForm = $this->createDeleteForm($playLog);
        return $this->render('playlog/show.html.twig', array(
            'playLog' => $playLog,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing playLog entity.
     *
     * @Route("/{id}/edit", name="playlog_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, PlayLog $playLog)
    {
        $deleteForm = $this->createDeleteForm($playLog);
        $editForm = $this->createForm('AppBundle\Form\PlayLogType', $playLog);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('playlog_edit', array('id' => $playLog->getId()));
        }

        return $this->render('playlog/edit.html.twig', array(
            'playLog' => $playLog,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a playLog entity.
     *
     * @Route("/{id}", name="playlog_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, PlayLog $playLog)
    {
        $form = $this->createDeleteForm($playLog);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($playLog);
            $em->flush();
        }

        return $this->redirectToRoute('playlog_index');
    }

    /**
     * Creates a form to delete a playLog entity.
     *
     * @param PlayLog $playLog The playLog entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(PlayLog $playLog)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('playlog_delete', array('id' => $playLog->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
