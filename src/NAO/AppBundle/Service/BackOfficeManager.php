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
        $templating
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

    public function createUser(Request $request) {

        $user = new User();
        $form = $this->formfactory->create(AdminUserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Ajouter les roles
            $user->setRoles(array($user->getRole()));
            // Générer le mot de passe
            $generatedPass = uniqid('', true);
            $user->setPlainPassword($generatedPass);
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
            $this->session->getFlashBag()->add('notice', 'Utilisateur bien ajouté, il recevra un email contenant son mot de passe provisoire');

            // Pseudo de l'admin
            $admin = $this->security->getToken()->getUser();
            // Envoyer mail de confirmation avec le mot de passe provisoire
            $message = \Swift_Message::newInstance()
            ->setSubject('Création de votre compte par ' . $admin->getUsername())
            ->setFrom('nao@nicolasdubouilh.fr')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('NAOAppBundle:BackOffice:email.html.twig', array(
                    'user' => $user,
                    'admin' => $admin,
                    'pass' => $generatedPass,
                )),
                'text/html'
            )
        ;
        // Envoi du message
        $this->mailer->send($message);
            // Redirection
            $response = new RedirectResponse('admin');
            $response->send();
        }

        return $form->createView();
    }
}
