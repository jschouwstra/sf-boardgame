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
use Doctrine\DBAL\DBALException;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


/**
 * @Security("is_granted('ROLE_ADMIN')")
 * @Route("/admin")
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
    public function giveAdminRoleToUser()
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
            '<html><body>Rol toegevoegd: <br/><pre> ' . var_dump($user) . '</pre></body></html>'
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

        $game = new Game();
        $em = $this->getDoctrine()->getManager();
        $fill = 'fill()';
        $retrieveGameForm = $this->createFormBuilder()
            ->add('bgg_id_to_retrieve', TextType::class
            )
            ->getForm();

        $form = $this->createFormBuilder($game)
            ->add('bgg_id', TextType::class, array(
                    'attr' => array(
                        'id' => 'bgg_id_retrieved',
                        'label' => false,
                        'class' => 'form-control',
                        'readonly' => true
                    ),
                )
            )
            ->add('name', TextType::class, array(
                    'attr' => array(
                        'id' => 'name',
                        'label' => 'Name',
                        'class' => 'form-control',
                        'readonly' => true

                    ),
                )
            )
            ->add('playtime', TextType::class, array(
                    'attr' => array(
                        'id' => 'playtime',
                        'label' => 'Play time',
                        'class' => 'form-control',
                        'readonly' => true
                    ),
                )
            )
            ->add('image', TextType::class, array(
                    'attr' => array(
                        'id' => 'image',
                        'label' => 'Image',
                        'class' => 'form-control',
                        'readonly' => true
                    ),
                )
            )
            ->add('no_of_players', TextType::class, array(
                    'attr' => array(
                        'id' => 'no_of_players',
                        'label' => 'Players',
                        'class' => 'form-control',
                        'readonly' => true
                    ),
                )
            )
            ->add('isExpansion', TextType::class, array(
                    'attr' => array(
                        'id' => 'isExpansion',
                        'label' => 'Expansion',
                        'class' => 'form-control',
                        'readonly' => true
                    ),
                )
            )
            ->add('submit', SubmitType::class, array(
                    'attr' => array(
                        'label' => 'Add game',
                        'class' => 'form-control btn btn-success'
                    ),
                )
            )
            ->getForm();
        $retrieveGameForm->handleRequest($request);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $game = $form->getData();
            try {
                $em->persist($game);
                $em->flush();
                $message = 'Object succesfully added: '.$form->get('name')->getData();
                $this->addFlash('success', $message);
                return $this->redirect($this->generateUrl('game_new_with_bgg_id'));

            } catch (\Exception $e) {
                $duplicateEntry = '23000';
                if (strpos($e->getMessage(), $duplicateEntry)) {
                    $message = 'Bgg ID: ' . $form->get('bgg_id')->getData() . ' already exists.';
                    $this->addFlash('warning', $message);
                }


            }
        }

        return $this->render('admin/game/new_game_bgg_input.html.twig', array(
            'retrieveGameForm' => $retrieveGameForm->createView(),
            'fillGameForm' => $form->createView(),
            'game' => $game
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