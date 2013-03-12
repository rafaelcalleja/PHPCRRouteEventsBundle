<?php

namespace RC\PHPCRRouteEventsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('RCPHPCRRouteEventsBundle:Default:index.html.twig', array('name' => $name));
    }
}
