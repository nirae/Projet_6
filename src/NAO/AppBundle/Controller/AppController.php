<?php

namespace NAO\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use NAO\AppBundle\Entity\Observation;
use NAO\AppBundle\Form\ObservationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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
        $form = $this->get('nao_app.backoffice_manager')->add($request);

        return array('form' => $form);
    }

    /**
    * @Template("NAOAppBundle:BackOffice:validations.html.twig")
    *
    * @Security("has_role('ROLE_NATUR')")
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
    *
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function adminAction()
    {
        return array();
    }
}
