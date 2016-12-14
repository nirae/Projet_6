<?php

namespace NAO\BackOfficeBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BackOfficeController extends Controller
{
    public function observationsAction()
    {
        return $this->render('NAOBackOfficeBundle:BackOffice:observations.html.twig');
    }

    public function addAction()
    {
        return $this->render('NAOBackOfficeBundle:BackOffice:add.html.twig');
    }

    public function validationsAction()
    {
        return $this->render('NAOBackOfficeBundle:BackOffice:validations.html.twig');
    }

    public function accountAction()
    {
        return $this->render('NAOBackOfficeBundle:BackOffice:account.html.twig');
    }

    public function adminAction()
    {
        return $this->render('NAOBackOfficeBundle:BackOffice:admin.html.twig');
    }

}
