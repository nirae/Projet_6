<?php

namespace NAO\AppBundle\BackOfficeManagerService;

class BackOfficeManager
{
    private $em;
    private $formfactory;

    private $obs;
    private $form;

    public function __construct($em, $formfactory) {
        $this->em = $em;
        $this->formfactory = $formfactory;
    }

    // Si le formulaire à été soumis
    // Fait tout puis retourne true au controller pour redirection
    public function addObsFormValidation($request, $user) {

        // Si le form ou l'entité n'a pas encore été crée
        // C'est que le formulaire n'a pas encore été affiché par le controller
        // On sort directement
        if ($this->form === null || $this->obs === null) {
            return;
        } else {
            // Si le formulaire a bien été soumis et est valide
            $this->form->handleRequest($request);
            if ($this->form->isSubmitted() && $this->form->isValid()) {
                // Si il y a bien un utilisateur connecté
                if ($user !== null) {

                    $this->obs->setOwner($user);
                    $user->addObservation($this->obs);
                    // Flush
                    $this->em->persist($this->obs);
                    $this->em->flush();
                }
                return true;
            }
        }
    }

    //Création du formulaire
    public function formCreation() {
        $this->obs = new \NAO\AppBundle\Entity\Observation();
        $this->form = $this->formfactory->create(\NAO\AppBundle\Form\ObservationType::class, $this->obs);
        return true;
    }

    public function getForm() {
        return $this->form;
    }

    public function getObservation() {
        return $this->obs;
    }
}
