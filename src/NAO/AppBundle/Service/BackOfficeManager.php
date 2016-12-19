<?php

namespace NAO\AppBundle\Service;

use NAO\AppBundle\Entity\Observation;
use NAO\AppBundle\Form\ObservationType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;

class BackOfficeManager
{
    private $em;
    private $formfactory;
    private $security;
    private $session;

    public function __construct(
        EntityManager $em,
        FormFactory $formfactory,
        $security,
        Session $session
    )
    {
        $this->em = $em;
        $this->formfactory = $formfactory;
        $this->security = $security;
        $this->session = $session;
    }

    public function add($request) {
        $obs = new Observation();
        $form = $this->formfactory->create(ObservationType::class, $obs);

        $user = $this->security->getToken()->getUser();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Si il y a bien un utilisateur connecté
            if ($user !== null) {

                $obs->setOwner($user);
                $user->addObservation($obs);
                // Flush
                $this->em->persist($obs);
                $this->em->flush();
                // Flash Message
                $this->session->getFlashBag()->add('notice', 'Observation bien ajoutée');
                // Redirection
                $response = new RedirectResponse('mes-observations');
                $response->send();
            }
        }

        return $form->createView();
    }
}
