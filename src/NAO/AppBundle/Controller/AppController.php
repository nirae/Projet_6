<?php

namespace NAO\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use NAO\AppBundle\Entity\Observation;
use NAO\AppBundle\Entity\User;
use NAO\AppBundle\Form\ObservationType;
use NAO\AppBundle\Form\UserType;
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

        $obs = new Observation();
        $form = $this->createForm(ObservationType::class, $obs);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Récupère l'user
            $user = $this->getUser();

            if (null != $user) {

                $obs->setOwner($user);
                $user->addObservation($obs);

                $em = $this->getDoctrine()->getManager();
                $em->persist($obs);
                $em->flush();
            }

            return $this->redirectToRoute('nao_back_office_observations');
        }

        return array(
            'form' => $form->createView(),
        );
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

    /**
    * @Template("NAOAppBundle:BackOffice:login.html.twig")
    */
    public function loginAction(Request $request)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return $this->redirectToRoute('nao_back_office_observations');
        }

        // Ce service permet de récupérer le nom d'user et l'erreur si il y a
        $authUtils = $this->get('security.authentication_utils');

        return array(
            'last_username' => $authUtils->getLastUsername(),
            'error' => $authUtils->getLastAuthenticationError(),
        );
    }

    /**
    * @Template("NAOAppBundle:BackOffice:register.html.twig")
    */
    public function registerAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            // Encoder le mot de passe
            $password = $this->get('security.password_encoder')
            ->encodePassword($user, $user->getPlainPassword())
            ;
            $user->setPassword($password);
            // Flush
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            // Envoyer mail de confirmation

            $this->addFlash('notice', 'Bienvenue !');

            return $this->redirectToRoute('nao_back_office_observations');
        }

        return array(
            'form' => $form->createView(),
        );
    }
}
