<?php

namespace NAO\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BackOfficeController extends Controller
{
    public function indexAction()
    {
        return $this->render('NAOBackOfficeBundle:BackOffice:index.html.twig');
    }
}
