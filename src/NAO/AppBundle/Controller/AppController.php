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
    public function validationsAction()
    {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository("NAOAppBundle:Observation");

        $waitingObs = $repository->findBy(["status" => "En attente"]);

        // Reception AJAX
        // Formulaire html simple commentaire obligatoire + bouton valider ou refuser
        // Le clic sur un des boutons enregistre une data "validée" ou "refusée"
        // Change le statut en back
        // Envoi en AJAX des données puis réponse OK qui supprime l'observation de la liste

        return array(
            "observations" => $waitingObs,
        );
    }

    /**
    * @Template("NAOAppBundle:BackOffice:account.html.twig")
    */
    public function accountAction($request)
    {
        return array();
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
    public function adminaddAction(Request $request) {
        // On génère un mot de passe temporaire et un mail est envoyé pour lui indiquer de le changer dans ses preferences
        $form = $this->get('nao_app.user_manager')->createUser($request);

        return array(
            'form' => $form,
        );
    }
}
