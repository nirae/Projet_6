<?php

namespace NAO\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use NAO\AppBundle\Entity\Observation;
use NAO\AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class AppController extends Controller
{
    /**
     * @Template("NAOAppBundle:App:index.html.twig")
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Template("NAOAppBundle:BackOffice:observations.html.twig")
     */
    public function observationsAction()
    {
        return array();
    }

    /**
     * @Template("NAOAppBundle:BackOffice:add.html.twig")
     */
    public function addAction(Request $request)
    {

        $obs = new Observation();
        $form = $this->createForm(ObservationType::class, $obs);

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
            //
            // Flush
            return $this->redirectToRoute('nao_back_office_observations');
        }

        return array(
            'form' => $form->createView(),
        );
    }

    /**
     * @Template("NAOAppBundle:BackOffice:validations.html.twig")
     */
    public function validationsAction()
    {
        return array();
    }

    /**
     * @Template("NAOAppBundle:BackOffice:account.html.twig")
     */
    public function accountAction()
    {
        return array();
    }

    /**
     * @Template("NAOAppBundle:BackOffice:admin.html.twig")
     */
    public function adminAction()
    {
        return array();
    }
}
