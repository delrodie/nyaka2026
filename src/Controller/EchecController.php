<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Main\Participant;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/echec')]
class EchecController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private ManagerRegistry $doctrine
    )
    {
    }

    #[Route('/{matricule}', name: 'app_echec_show', methods: ['GET'])]
    public function show($matricule): Response
    {
        $emMain = $this->doctrine->getManager('default');

        $participant = $emMain->getRepository(Participant::class)->findOneBy([
            'matricule' => $matricule
        ]);
        return $this->render('frontend/wave_echec_paiement.html.twig',[
            'participant' => $participant,
        ]);
    }

    #[Route('/{slug}/recu', name: 'app_echec_recu', methods: ['GET'])]
    public function recu($slug): Response
    {
        $emMain = $this->doctrine->getManager('default');

        $participant = $emMain->getRepository(Participant::class)->findOneBy([
            'slug' => $slug
        ]);
        return $this->render('frontend/recu_echec.html.twig',[
            'participant' => $participant,
        ]);
    }
}
