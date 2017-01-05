<?php

namespace NAO\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

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

    public function confirmationAccountAction($id, $username, $email)
    {
        return $this->get('nao_app.user_manager')->confirmation($id, $username, $email);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function confirmationAdminAccountAction($id, $username, $email, $status)
    {
        return $this->get('nao_app.user_manager')->confirmationAdmin($id, $username, $email, $status);
    }

    /**
     * @Security("has_role('ROLE_ADMIN')")
     */
    public function resetPasswordAction($id, $username, $email)
    {
        return $this->get('nao_app.user_manager')->resetPassword($id, $username, $email);
    }
}
