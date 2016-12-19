<?php

namespace NAO\AppBundle\Service;

use NAO\AppBundle\Form\UserType;
use NAO\AppBundle\Entity\User;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class UserManager
{
    private $authChecker;
    private $authUtils;
    private $passEncoder;
    private $em;
    private $formfactory;
    private $session;

    public function __construct(
        $authChecker,
        $authUtils,
        $passEncoder,
        EntityManager $em,
        FormFactory $formfactory,
        Session $session
    )
    {
        $this->authChecker = $authChecker;
        $this->authUtils = $authUtils;
        $this->passEncoder = $passEncoder;
        $this->em = $em;
        $this->formfactory = $formfactory;
        $this->session = $session;
    }

    public function login() {
        if ($this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {

            $response = new RedirectResponse('backoffice/mes-observations');
            $response->send();
        }

        return array(
            'last_username' => $this->authUtils->getLastUsername(),
            'error' => $this->authUtils->getLastAuthenticationError(),
        );
    }

    public function register(Request $request) {
        $user = new User();
        $form = $this->formfactory->create(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encoder le mot de passe
            $password = $this->passEncoder->encodePassword(
                $user,
                $user->getPlainPassword()
            );
            // Hydrate l'entité avec le nouveau mdp encodé
            $user->setPassword($password);
            // Flush
            $this->em->persist($user);
            $this->em->flush();
            // Flash Message
            $this->session->getFlashBag()->add('notice', 'Bienvenue !');
            // Envoyer mail de confirmation
            // Redirection
            $response = new RedirectResponse('backoffice/mes-observations');
            $response->send();
        }

        return $form->createView();
    }
}
