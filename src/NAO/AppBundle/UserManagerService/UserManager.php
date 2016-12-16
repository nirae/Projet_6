<?php

use NAO\AppBundle\Form\UserType;
use NAO\AppBundle\Entity\User;

namespace NAO\AppBundle\UserManagerService;

class UserManager
{
    private $authChecker;
    private $authUtils;
    private $passEncoder;
    private $em;
    private $formfactory;

    private $user;
    private $form;

    public function __construct($authChecker, $authUtils, $passEncoder, $em, $formfactory)
    {
        $this->authChecker = $authChecker;
        $this->authUtils = $authUtils;
        $this->passEncoder = $passEncoder;
        $this->em = $em;
        $this->formfactory = $formfactory;
    }

    /*******************************************************************
                                LOGIN
    *******************************************************************/

    // Test si i l'utilisateur est déjà connecté
    // Retourne true pour redirection depuis le controller
    public function userAlreadyConnected() {

        if ($this->authChecker->isGranted('IS_AUTHENTICATED_REMEMBERED')) {
            return true;
        }
    }

    // Retourne nom d'user et erreur si il y a
    // Pour retourner à la vue directement
    public function usernameAndError() {

        return array(
            'last_username' => $this->authUtils->getLastUsername(),
            'error' => $this->authUtils->getLastAuthenticationError(),
        );
    }

    /*******************************************************************
                                REGISTER
    *******************************************************************/

    // Si le formulaire à été soumis
    // Fait tout puis retourne true au controller pour redirection
    public function formValidation($request) {

        // Si le form ou l'entité n'a pas encore été crée
        // C'est que le formulaire n'a pas encore été affiché par le controller
        // On sort directement
        if ($this->form === null || $this->user === null) {
            return;
        } else {
            // Si le formulaire a bien été soumis et est valide
            $this->form->handleRequest($request);
            if ($this->form->isSubmitted() && $this->form->isValid()) {
                // Encoder le mot de passe
                $password = $this->passEncoder->encodePassword(
                    $this->user,
                    $this->user->getPlainPassword()
                );
                // Hydrate l'entité avec le nouveau mdp encodé
                $this->user->setPassword($password);
                // Flush
                $this->em->persist($this->user);
                $this->em->flush();
                // Envoyer mail de confirmation

                return true;
            }
        }
    }

    //Création du formulaire
    public function formCreation() {
        $this->user = new \NAO\AppBundle\Entity\User();
        $this->form = $this->formfactory->create(\NAO\AppBundle\Form\UserType::class, $this->user);
        return true;
    }

    public function getForm() {
        return $this->form;
    }

    public function getUser() {
        return $this->user;
    }
}
