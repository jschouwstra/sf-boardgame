<?php
/**
 * Created by PhpStorm.
 * User: Jelle
 * Date: 29-1-2018
 * Time: 16:49
 */

namespace AppBundle\Controller;


use AppBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


///**
// * @Route("admin")
// */

class AdminController extends Controller
{
    /**
     * @Route("test/giverole")
     */
    public function giveAdminroleToUser(){

        /**
         * @var User $user
         */
        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $user->addRole("ROLE_ADMIN");
        $em->persist($user);
        $em->flush();
    }

    /**
     * @Route("admin/index")
     */
    public function index(){


        return $this->render('admin/index.html.twig', array(

        ));
    }
}