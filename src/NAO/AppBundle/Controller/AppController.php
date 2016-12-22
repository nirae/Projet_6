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
    * @Template("NAOAppBundle:BackOffice:post.html.twig")
    */
    public function postModifPasswordAction(Request $request)
    {
        $this->get('nao_app.user_manager')->postModifPass($request);

        return;
    }

    /**
    * @Template("NAOAppBundle:BackOffice:post.html.twig")
    */
    public function postModifAccountAction(Request $request)
    {
        $this->get('nao_app.user_manager')->postModifUser($request);

        return;
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
