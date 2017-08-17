<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

    /**
     * Social controller.
     *
     * @Route("social")
     *
     */
class SocialController extends Controller
{

/**
 * @Route("/", name="social_index")
 * @Method("GET")

 */

    public function indexAction()
    {
        return $this->render('social/index.html.twig');
    }
}
