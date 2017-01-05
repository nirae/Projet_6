<?php

namespace NAO\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class AppController extends Controller
{
    /**
    * @Template("NAOAppBundle:App:index.html.twig")
    */
    public function indexAction(Request $request)
    {
        $form = $this->get('nao_app.backoffice_manager')->index($request);

        return array('form' => $form);
    }

    public function ajaxIndexAction(Request $request)
    {
        return $this->get('nao_app.backoffice_manager')->postIndex($request);
    }

    /**
    * @Template("NAOAppBundle:BackOffice:observations.html.twig")
    *
    * @Security("has_role('ROLE_USER')")
    */
    public function observationsAction()
    {
        return;
    }

    /**
    * @Template("NAOAppBundle:BackOffice:add.html.twig")
    *
    * @Security("has_role('ROLE_USER')")
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
    public function validationsAction(Request $request)
    {
        return $this->get('nao_app.backoffice_manager')->validations($request);
    }

    /**
     * @Security("has_role('ROLE_NATUR')")
     */
    public function postValidationsAction($id, Request $request) {

        $this->get('nao_app.backoffice_manager')->postValidations($id, $request);

        return;
    }

    /**
    * @Template("NAOAppBundle:BackOffice:account.html.twig")
    * 
    * @Security("has_role('ROLE_USER')")
    */
    public function accountAction(Request $request)
    {
        // Changer les infos
        $infosForm = $this->get('nao_app.user_manager')->modifUser();
        // Changer le mot de passe
        $newpassForm = $this->get('nao_app.user_manager')->modifPass();
        return array(
            'infosform' => $infosForm,
            'passform' => $newpassForm,
        );
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function postModifPasswordAction(Request $request)
    {
        return $this->get('nao_app.user_manager')->postModifPass($request);
    }

    /**
     * @Security("has_role('ROLE_USER')")
     */
    public function postModifAccountAction(Request $request)
    {
        return $this->get('nao_app.user_manager')->postModifUser($request);
    }

    /**
    * @Template("NAOAppBundle:BackOffice:admin.html.twig")
    *
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function adminAction(Request $request)
    {
        $users = $this->get('nao_app.user_manager')->listUsers();

        return array(
            'users' => $users,
        );
    }

    /**
    * @Template("NAOAppBundle:BackOffice:admin-add.html.twig")
    *
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function adminAddAction(Request $request) {

        $form = $this->get('nao_app.user_manager')->createUser($request);

        return array(
            'form' => $form,
        );
    }
}
