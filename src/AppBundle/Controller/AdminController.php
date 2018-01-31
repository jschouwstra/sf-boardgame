<?php
/**
 * Created by PhpStorm.
 * User: Jelle
 * Date: 29-1-2018
 * Time: 16:49
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use function var_export;


/**
 * @Route("admin")
 */

class AdminController extends Controller
{
    /**
     * @Route("/index")
     */
    public function index()
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/index.html.twig', array());
    }

    /**
     * @Route("test/giverole")
     */
    public function giveAdminroleToUser()
    {

        /**
         * @var User $user
         */
        $user = $this->getUser();
        $roles = ["ROLE_ADMIN"];
        $user->setRoles($roles);
        $em = $this->getDoctrine()->getManager();
//        $user->addRole("ROLE_ADMIN");
        $em->persist($user);
        $em->flush();
        return new Response(
            '<html><body>Roll toegevoegd: <br/><pre> '.var_dump($user).'</pre></body></html>'
        );
    }


    /**
     * Creates a new game entity.
     *
     * @Route("/new-game/with-bgg-id", name="game_new_with_bgg_id")
     * @Method({"GET", "POST"})
     */
    public function insertNewGameWithBggId(Request $request)
    {
        $fill = 'fill()';
        $form = $this->createFormBuilder()
            ->add('bgg_id', TextType::class, array(
                    'attr' => array(
                        'class' => 'form-control',
                        'id' => 'fill',
                    ),
                )
            )
            ->add('name', TextType::class, array(
                    'attr' => array(
                        'id' => 'name',
                        'label' => 'Name',
                        'class' => 'form-control'
                    ),
                )

            )
            ->getForm();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            echo $form->get('bgg_id')->getData();
        }

        return $this->render('admin/new_game_bgg_input.html.twig', array(
            'form' => $form->createView(),
        ));
    }


    /**
     * Creates a new game entity.
     *
     * @Route("admin/new-game", name="game_new")
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

}