<?php

namespace NAO\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class SecurityController extends Controller
{
    /**
    * @Template("NAOAppBundle:Security:login.html.twig")
    */
    public function loginAction(Request $request)
    {
        $um = $this->get('nao_app.user_manager');

        if ($um->userAlreadyConnected()) {
            return $this->redirectToRoute('nao_back_office_observations');
        }

        return $um->usernameAndError();
    }

    /**
    * @Template("NAOAppBundle:Security:register.html.twig")
    */
    public function registerAction(Request $request)
    {
        $um = $this->get('nao_app.user_manager');
        $um->formCreation();

        if ($um->formValidation($request)) {

            $this->addFlash('notice', 'Bienvenue !');
            return $this->redirectToRoute('nao_back_office_observations');
        }

        return array('form' => $um->getForm()->createView());
    }
}
