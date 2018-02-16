<?php
/**
 * Created by PhpStorm.
 * User: Jelle
 * Date: 29-1-2018
 * Time: 16:49
 */

namespace AppBundle\Controller;


use AppBundle\Controller\GameController;
use AppBundle\Entity\Expansion;
use AppBundle\Entity\Game;
use AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use function is_numeric;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use function var_dump;


/**
 * @Security("is_granted('ROLE_ADMIN')")
 * @Route("/admin")
 */
class AdminController extends Controller
{
    /**
     * @Route("/index")
     */
    public function index(Request $request)
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        return $this->render('admin/index.html.twig', array(
            'latestGames' => $this->getLatestGamesAction(5),
            'latestExpansions' => $this->getLatestExpansionsAction(5),
            'totalExpansions' => count($this->getAllExpansions()),
            'totalGames' => count($this->getAllGames())
        ));
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
     * @Route("/game/index", name="admin_game_index")
     * @Method({"GET", "POST"})
     */
    public function gameIndexAction()
    {
        $games = $this->getAllGames();
        return $this->render('admin/game/index.html.twig', array(
            'games' => $games
        ));
    }

    public function getAllGames()
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $query = $qb->select('g')
            ->from(Game::class, 'g')
            ->orderBy('g.name', 'asc')
            ->getQuery();

        $games = $query->getResult();
        return $games;
    }

    public function getAllExpansions(){
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();
        $qb = $em->createQueryBuilder();

        $query = $qb->select('exp')
            ->from(Expansion::class, 'exp')
            ->orderBy('exp.name', 'asc')
            ->getQuery();

        $expansions = $query->getResult();
        return $expansions;
    }

    public function getLatestGamesAction($quantity)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();

        $query = $qb->select('g')
            ->from(Game::class, 'g')
            ->orderBy('g.id', 'desc')
            ->setMaxResults($quantity)
            ->getQuery();

        $games = $query->getResult();
        return $games;
    }

    public function getLatestExpansionsAction($quantity)
    {
        /**
         * @var EntityManager $em
         */
        $em = $this->getDoctrine()->getManager();

        $qb = $em->createQueryBuilder();

        $query = $qb->select('exp')
            ->from(Expansion::class, 'exp')
            ->orderBy('exp.id', 'desc')
            ->setMaxResults($quantity)
            ->getQuery();

        $games = $query->getResult();
        return $games;
    }

    /**
     * Creates a new game entity.
     *
     * @Route("/new-expansion/with-bgg-id", name="expansion_new_with_bgg_id")
     * @Method({"GET", "POST"})
     */
    public function insertNewExpansionWithBggId(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $fill = 'fill()';
        $retrieveExpansionForm = $this->createFormBuilder()
            ->add('bgg_id_to_retrieve', TextType::class
            )
            ->add('isExpansion_to_retrieve', TextType::class, array(
                    'attr' => array(
                        'id' => 'isExpansion_to_retrieve',
                        'label' => 'Expansion',
                        'class' => 'form-control',
                        'readonly' => true
                    ),
                )
            )
            ->getForm();
        $expansion = new Expansion();

        $form = $this->createFormBuilder($expansion)
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
                        'id' => 'bggIsExpansion',
                        'label' => 'Expansion',
                        'class' => 'form-control',
                        'readonly' => true,
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
        $retrieveExpansionForm->handleRequest($request);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $expansion = $form->getData();
            //Check if it's a game
            try {
                if ($form->get('isExpansion')->getData() == 'true' ) {

                    $em->persist($expansion);
                    $em->flush();
                    $message = 'Object succesfully added: ' . $form->get('name')->getData();
                    $this->addFlash('success', $message);
                    return $this->redirect($this->generateUrl('expansion_new_with_bgg_id'));
                } else {
                    $this->addFlash('success', 'This is not a expansion.');

                }
            } catch (\Exception $e) {
                $duplicateEntry = '23000';
                if (strpos($e->getMessage(), $duplicateEntry)) {
                    $message = $form->get('name')->getData() . ' already exists.';
                    $this->addFlash('warning', $message);
                }
            }
        }
        return $this->render('admin/expansion/new_expansion_bgg_input.html.twig', array(
            'retrieveGameForm' => $retrieveExpansionForm->createView(),
            'fillGameForm' => $form->createView(),
        ));
    }

    /**
     * Creates a new game entity.
     *
     * @Route("/new-game/with-bgg-id", name="game_new_with_bgg_id")
     * @Method({"GET", "POST"})
     */
    public function insertNewGameWithBggId(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $fill = 'fill()';
        $retrieveGameForm = $this->createFormBuilder()
            ->add('bgg_id_to_retrieve', TextType::class
            )
            ->add('isExpansion_to_retrieve', TextType::class, array(
                    'attr' => array(
                        'id' => 'isExpansion_to_retrieve',
                        'label' => 'Expansion',
                        'class' => 'form-control',
                        'readonly' => true
                    ),
                )
            )
            ->getForm();
        $game = new Game();

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
                        'id' => 'bggIsExpansion',
                        'label' => 'Expansion',
                        'class' => 'form-control',
                        'readonly' => true,
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
            //Check if it's a game
            try {
                if ($form->get('isExpansion')->getData() == 'false' ) {

                    $em->persist($game);
                    $em->flush();
                    $message = 'Object succesfully added: ' . $form->get('name')->getData();
                    $this->addFlash('success', $message);
                    return $this->redirect($this->generateUrl('game_new_with_bgg_id'));
                } else {
                    $this->addFlash('success', 'This is not a game.');

                }
            } catch (\Exception $e) {
                $duplicateEntry = '23000';
                if (strpos($e->getMessage(), $duplicateEntry)) {
                    $message = $form->get('name')->getData() . ' already exists.';
                    $this->addFlash('warning', $message);
                }
            }
        }
        return $this->render('admin/game/new_game_bgg_input.html.twig', array(
            'retrieveGameForm' => $retrieveGameForm->createView(),
            'fillGameForm' => $form->createView(),
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