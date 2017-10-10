<?php

namespace InterNations\UserGroupBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('UserGroupBundle:Default:index.html.twig');
    }
}
