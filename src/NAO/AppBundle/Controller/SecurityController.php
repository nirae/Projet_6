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
        // Array of last Username and Error if there is one
        $datas = $this->get('nao_app.user_manager')->login();

        return $datas;
    }

    /**
    * @Template("NAOAppBundle:Security:register.html.twig")
    */
    public function registerAction(Request $request)
    {
        $form = $this->get('nao_app.user_manager')->register($request);

        return array('form' => $form);
    }
}
