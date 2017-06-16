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
        /** @var User $userObject */
        $userObject = $this->getUser();
      //  var_dump($userObject);

        if (!$form->isSubmitted()) {
            // games selecteren
            $form["game"]->setData($userObject->getGames());
        }

        if ($form->isSubmitted() && $form->isValid()) {
//            print_r ("<br><br><br><br><br><br><br><br><br><br><br><br>".$request->request->get('game'));
//            die($request->request->all());
            $logger = $this->get('logger');
//            $logger->debug("test games selectbox".var_dump($form));

            $em = $this->getDoctrine()->getManager();




//            $logger->debug("hello world");


            /** @var  $game */
            $gameArray = $form["game"]->getData();
           // $logger->debug(var_dump($game));
//            $logger->debug(var_dump($game['id']));
//            $logger->debug(var_dump($game->id));


            //$user = new User();
            $userObject->removeAllGames();
            foreach ($gameArray as $game) {
                $userObject->addGame($game);
            }
//            $user = $em->getRepository('AppBundle:User')
//                ->find(['id' => $userObject->getId()]);


//            $user->addGame($game);
            $em->persist($userObject);
            $em->flush();
            return $this->redirectToRoute('game_index');
        }


        return $this->render('user/addGame.html.twig', array(
            'form' => $form->createView()
        ));
    }
}
