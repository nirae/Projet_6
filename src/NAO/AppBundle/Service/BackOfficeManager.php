<?php

namespace NAO\AppBundle\Service;

use NAO\AppBundle\Entity\Observation;
use NAO\AppBundle\Form\Model\Index;
use NAO\AppBundle\Form\ObservationType;
use NAO\AppBundle\Entity\User;
use NAO\AppBundle\Form\AdminUserType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Bundle\TwigBundle\TwigEngine;
use NAO\AppBundle\Form\ValidationsType;
use NAO\AppBundle\Form\IndexType;
use Symfony\Component\HttpFoundation\JsonResponse;

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

    public function index(Request $request)
    {
        $index = new Index();
        $form = $this->formfactory->create(IndexType::class, $index);

        return $form->createView();
    }

    public function postIndex(Request $request)
    {
        if ($request->isMethod('POST')) {
            // Récupère l'id envoyé via ajax
            $speciesId = $request->get('id');

            //Récupère les observations correspondantes
            $repository = $this->em->getRepository('NAOAppBundle:Observation');
            // Si l'id = all on récupère tout
            // Sinon que les observations concernant l'id choisi
            if ($speciesId === "all") {
                $observations = $repository->findBy(
                    array(
                        'status' => 'Validée'
                    )
                );
            } else {
                $observations = $repository->findBy(
                    array(
                        'species' => $speciesId,
                        'status' => 'Validée'
                    )
                );
            }

            // Si il n'y a pas d'observations
            if (count($observations) === 0) {
                return new JsonResponse(array(
                    'response' => false,
                ));
            }

            $listObservations = array();
            foreach ($observations as $obs) {
                $listObservations[] = array(
                    'id' => $obs->getId(),
                    'date' => date('d/m/Y', $obs->getDate()->getTimestamp()),
                    'latitude' => $obs->getLatitude(),
                    'longitude' => $obs->getLongitude(),
                );
            }

            return new JsonResponse(array(
                'response' => $listObservations,
            ));
        }
    }

    public function add(Request $request)
    {
        $obs = new Observation();
        $form = $this->formfactory->create(ObservationType::class, $obs);

        $user = $this->security->getToken()->getUser();

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Si il y a bien un utilisateur connecté
            if ($user !== null) {

                // Si l'user à ROLE_NATUR, l'obs est validée directement
                if ($user->getRoles() == array('ROLE_NATUR') || $user->getRoles() == array('ROLE_ADMIN')) {
                    $obs->setStatus("Validée");
                }

                $obs->setOwner($user);
                $user->addObservation($obs);
                // Flush
                $this->em->persist($obs);
                $this->em->flush();
                // Flash Message
                $this->session->getFlashBag()->add('notice', 'Observation bien ajoutée !');
            }
        }

        return $form->createView();
    }

    public function validations(Request $request) {

        $repository = $this->em->getRepository("NAOAppBundle:Observation");
        $waitingObs = $repository->findBy(["status" => "En attente"]);

        $forms = array();
        // Tableau de formulaires
        // Chaque formulaire a comme clé l'id de l'observation qu'il concerne
        foreach ($waitingObs as $obs) {

            $form = $this->formfactory->create(ValidationsType::class, $obs);
            $forms[$obs->getId()] = $form->createView();
        }

        return array(
            'forms' => $forms,
            'observations' => $waitingObs,
        );
    }

    public function postValidations($id, $request) {
        // Récupère l'observation correspondante à l'id
        $repository = $this->em->getRepository("NAOAppBundle:Observation");
        $obs = $repository->find($id);
        // Création du formulaire
        $form = $this->formfactory->create(ValidationsType::class, $obs);
        $form->handleRequest($request);
        // Si le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Flush
            $this->em->persist($obs);
            $this->em->flush();
            // Flash Message
            $this->session->getFlashBag()->add('notice', 'Validation bien ajoutée');
            // Envoi email a l'user
            $message = \Swift_Message::newInstance()
            ->setSubject('Votre observation a été ' . $obs->getStatus())
            ->setFrom('nao@nicolasdubouilh.fr')
            ->setTo($obs->getOwner()->getEmail())
            ->setBody(
                $this->templating->render('NAOAppBundle:Email:validationobs.html.twig', array(
                    'obs' => $obs,
                )),
                'text/html'
            );
            // Envoi du message
            $this->mailer->send($message);
            // Redirection
            $response = new RedirectResponse('/backoffice/validations');
            $response->send();
        }
        // Redirige quand meme si le lien n'est pas bon
        $response = new RedirectResponse('/backoffice/validations');
        $response->send();
    }

}
