<?php

namespace App\Controller;

use App\Entity\LinkedInMessage;
use App\Repository\JobOfferRepository;
use App\Repository\LinkedInMessageRepository;
use Doctrine\ORM\EntityManagerInterface;
use Gemini;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
class LinkedInMessageController extends AbstractController
{
    #[Route('/linkedin-message/generate', name: 'app_linkedin_generate', methods: ['POST', 'GET'])]
    public function generate(EntityManagerInterface $em, JobOfferRepository $jobs): Response
    {
        $job = $jobs->findOneById($_GET['id']);
        $user = $job->getAppUser();

        if ($user === $this->getUser()) {
            $company = $job->getCompany();
            $title = $job->getTitle();
            $firstName = $user->getFirstName();
            $lastName = $user->getLastName();
            //GESTION DE GEMINI AI
            $yourApiKey = $this->getParameter('GEMINI_API_KEY');
            $client = Gemini::client($yourApiKey);
            $result = $client->geminiPro()->generateContent(
                "Je veux une message privé linkedin pour la compagnie " . $company . " pour le poste de " . $title . ". 
                Voici les informations pour aider la création de la lettre de motivation :
                Mon nom est " . $lastName . ' et prénom est ' . $firstName . ".
                Je voudrais qu'à chaque saut de ligne tu me rajoutes la balise html <br>. 
                "
            );

            $lm = new LinkedInMessage;
            $lm
                ->setContent($result->text())
                ->setAppUser($user)
                ->setJobOffer($job);
            $em->persist($lm);
            $em->flush();

            return $this->redirectToRoute('app_linkedin_show', ['linkedinMessage' => $lm, 'id' => $lm->getId()]);
        }
        return $this->redirectToRoute('app_home');
    }
    #[Route('/linkedin-message/{id}', name: 'app_linkedin_show', methods: 'GET')]
    public function show(int $id, LinkedInMessageRepository $lms): Response
    {
        $lm = $lms->findOneById($id);

        return $this->render('linkedin_message/show.html.twig', ['jobOfferName' => $lm->getJobOffer()->getTitle(), 'LMContent' => $lm->getContent()]);
    }

    #[Route('/linkedin-message/{id}/delete', name: 'app_linkedin_delete', methods: ['GET', 'POST'])]
    public function delete(int $id, EntityManagerInterface $em, LinkedInMessageRepository $lms): Response
    {
        $lm = $lms->findOneById($id);

        if ($lm->getAppUser() !== $this->getUser()) {

            $this->addFlash('error', 'You are not authorized to edit this linkedin message');
            return $this->redirectToRoute('app_home');
        }

        $em->remove($lm);
        $em->flush();

        $this->addFlash('success', 'Your linkedin message has been updated');
        return $this->redirectToRoute('app_dashboard');
    }
}
