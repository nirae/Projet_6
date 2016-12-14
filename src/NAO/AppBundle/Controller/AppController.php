<?php

namespace NAO\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use NAO\AppBundle\Entity\Observation;
use NAO\AppBundle\Form\ObservationType;

class AppController extends Controller
{
    public function indexAction()
    {
        return $this->render('NAOAppBundle:App:index.html.twig');
    }

    public function observationsAction()
    {
        return $this->render('NAOAppBundle:BackOffice:observations.html.twig');
    }

    public function addAction(Request $request)
    {

        $obs = new Observation();
        $form = $this->createForm(ObservationType::class, $obs);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            // Flush
            return $this->redirectToRoute('nao_back_office_observations');
        }

        return $this->render('NAOAppBundle:BackOffice:add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function validationsAction()
    {
        return $this->render('NAOAppBundle:BackOffice:validations.html.twig');
    }

    public function accountAction()
    {
        return $this->render('NAOAppBundle:BackOffice:account.html.twig');
    }

    public function adminAction()
    {
        return $this->render('NAOAppBundle:BackOffice:admin.html.twig');
    }
}
