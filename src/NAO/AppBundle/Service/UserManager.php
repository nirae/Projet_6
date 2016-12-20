<?php

namespace NAO\AppBundle\Service;

use NAO\AppBundle\Form\UserType;
use NAO\AppBundle\Entity\User;
use NAO\AppBundle\Form\AdminUserType;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserManager
{
    private $authChecker;
    private $authUtils;
    private $passEncoder;
    private $em;
    private $formfactory;
    private $session;
    private $mailer;
    private $templating;
    private $router;
    private $security;

    public function __construct(
        $authChecker,
        $authUtils,
        $passEncoder,
        EntityManager $em,
        FormFactory $formfactory,
        Session $session,
        \Swift_Mailer $mailer,
        TwigEngine $templating,
        $router,
        $security
    )
    {
        $this->authChecker = $authChecker;
        $this->authUtils = $authUtils;
        $this->passEncoder = $passEncoder;
        $this->em = $em;
        $this->formfactory = $formfactory;
        $this->session = $session;
        $this->mailer = $mailer;
        $this->templating = $templating;
        $this->router = $router;
        $this->security = $security;
    }
    // Connexion
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

    // Inscription de l'user via le formulaire
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
            $this->session->getFlashBag()->add('notice', 'Un email contenant le lien de confirmation vous a été envoyé');
            // Création lien de confirmation
            $link = $this->router->generate(
                'nao_back_office_confirmation',
                array(
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                ),
                UrlGeneratorInterface::ABSOLUTE_URL
                );
            // Envoyer mail de confirmation
            $message = \Swift_Message::newInstance()
            ->setSubject('Confirmation de création de votre compte')
            ->setFrom('nao@nicolasdubouilh.fr')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('NAOAppBundle:Email:activation.html.twig', array(
                    'link' => $link,
                    'user' => $user,
                )),
                'text/html'
            );
            // Envoi du message
            $this->mailer->send($message);
            // Redirection
            $response = new RedirectResponse('backoffice/mes-observations');
            $response->send();
        }

        return $form->createView();
    }

    // Création d'un utilisateur par l'admin
    public function createUser(Request $request) {

        $user = new User();
        $form = $this->formfactory->create(AdminUserType::class, $user);

        $form->handleRequest($request);
        // Si Formulaire reçu
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
            $this->session->getFlashBag()->add(
                    'notice',
                    'Utilisateur bien ajouté, il recevra un email contenant son mot de passe provisoire'
                );

            // Pseudo de l'admin
            $admin = $this->security->getToken()->getUser();

            // Création lien de confirmation
            $link = $this->router->generate(
                'nao_back_office_confirmation',
                array(
                    'id' => $user->getId(),
                    'username' => $user->getUsername(),
                    'email' => $user->getEmail(),
                ),
                UrlGeneratorInterface::ABSOLUTE_URL
                );

            // Envoyer mail de confirmation avec le mot de passe provisoire
            $message = \Swift_Message::newInstance()
            ->setSubject('Création de votre compte par ' . $admin->getUsername())
            ->setFrom('nao@nicolasdubouilh.fr')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('NAOAppBundle:Email:activation.twig', array(
                    'user' => $user,
                    'admin' => $admin,
                    'pass' => $generatedPass,
                    'link' => $link,
                )),
                'text/html'
            );
            // Envoi du message
            $this->mailer->send($message);

            // Redirection
            $response = new RedirectResponse('admin');
            $response->send();
        }

        return $form->createView();
    }

    // Liste les utilisateurs selon les roles
    public function listUsers() {

        $users = $this->em->getRepository('NAOAppBundle:User')->findAll();

        $amateurs = array();
        $naturs = array();
        $admins = array();

        foreach ($users as $user) {
            if (in_array('ROLE_USER', $user->getRoles())) {
                $amateurs[] = $user;
            } elseif (in_array('ROLE_NATUR', $user->getRoles())) {
                $naturs[] = $user;
            } elseif (in_array('ROLE_ADMIN', $user->getRoles())) {
                $admins[] = $user;
            }

        }

        return array(
            'amateurs' => $amateurs,
            'naturs' => $naturs,
            'admins' => $admins,
        );
    }

    // Confirmation du compte
    public function confirmation($id, $username, $email) {
        //Récupère l'user
        $user = $this->em->getRepository("NAOAppBundle:User")->find($id);
        // Si l'user est déjà activé
        if ($user->isEnabled() === true) {
            // Flash message
            $this->session->getFlashBag()->add('notice', 'Votre compte a déjà été activé !');
            // Redirection
            return $user;
        }
        // Si l'user correspond bien au lien
        if ($user->getUsername() == $username && $user->getEmail() == $email) {
            // Activation
            $user->activate();
            // Flush
            $this->em->persist($user);
            $this->em->flush();
            // Mail
            $message = \Swift_Message::newInstance()
            ->setSubject('Votre compte a bien été confirmé')
            ->setFrom('nao@nicolasdubouilh.fr')
            ->setTo($user->getEmail())
            ->setBody(
                $this->templating->render('NAOAppBundle:Email:confirmation.html.twig', array(
                    'user' => $user,
                )),
                'text/html'
            );
            // Envoi du message
            $this->mailer->send($message);
            // Flash message
            $this->session->getFlashBag()->add('notice', 'Votre compte a bien été activé, vous pouvez désormais vous connecter');
            // Redirection
            return $user;
        } else {
            // Flash message
            $this->session->getFlashBag()->add('notice', 'Erreur');
            // Redirection
            return $user;
        }
    }
}
