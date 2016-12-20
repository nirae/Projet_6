<?php

namespace NAO\AppBundle\Service;

use NAO\AppBundle\Entity\Observation;
use NAO\AppBundle\Form\ObservationType;
use NAO\AppBundle\Entity\User;
use NAO\AppBundle\Form\AdminUserType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\TwigBundle\TwigEngine;

class BackOfficeManager
{
    private $em;
    private $formfactory;
    private $security;
    private $session;
    private $passEncoder;
    private $mailer;
    private $templating;

    public function __construct(
        EntityManager $em,
        FormFactory $formfactory,
        $security,
        Session $session,
        $passEncoder,
        \Swift_Mailer $mailer,
        TwigEngine $templating
    )
    {
        $this->em = $em;
        $this->formfactory = $formfactory;
        $this->security = $security;
        $this->session = $session;
        $this->passEncoder = $passEncoder;
        $this->mailer = $mailer;
        $this->templating = $templating;
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
