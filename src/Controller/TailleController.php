<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Main\Participant;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/taille')]
class TailleController extends AbstractController
{

    public function __construct(
        private HttpClientInterface $httpClient,
        private ManagerRegistry $doctrine
    )
    {
    }

    #[Route('/', name: 'app_taille_recherche', methods: ['GET', 'POST'])]
    public function recherche(Request $request): Response
    {
        $matricule = trim((string) $request->request->get('matricule', '')); //dump($matricule);

        // Si aucun matricule → afficher le formulaire
        if ($matricule === '') { //dump('ici');
            return $this->render('frontend/taille_recherche.html.twig');
        } //dump('laba');

        $em = $this->doctrine->getManager();
        $participant = $em->getRepository(Participant::class)->findOneBy([
            'matricule' => $matricule,
        ]);

        // Participant non trouvé
        if (!$participant) {
            sweetalert()->error("Matricule introuvable dans la liste des participants");
            return $this->redirectToRoute('app_home');
        }

        // Vérification du paiement
        if ($participant->getWavePaymentStatus() !== 'succeeded') {
            sweetalert()->error("Le matricule associé n'a pas encore effectué de paiement Wave");
            return $this->redirectToRoute('app_home');
        }
        //dd($participant);

        // Redirection selon la taille
        if ($participant->getTaille() !== '') {
            return $this->redirectToRoute('app_recu_show', [
                'matricule' => $participant->getSlug(),
            ]);
        }

        return $this->redirectToRoute('app_taille_update', [
            'matricule' => $participant->getSlug(), // ✅ correction ici
        ]);
    }

    #[Route('/{matricule}', name:'app_taille_update', methods: ['GET','POST'])]
    public function update(Request $request, $matricule)
    {
        $em = $this->doctrine->getManager();
        $participant = $em->getRepository(Participant::class)->findOneBy([
            'slug' => $matricule,
        ]);


        $reqTaille = trim((string) $request->request->get('tailleAine'));
        if ($reqTaille === '') {
            return $this->render('frontend/taille_modification.html.twig',[
                'participant' => $participant
            ]);
        }

        $participant->setTaille($reqTaille);
        $em->flush();

        return $this->redirectToRoute('app_recu_show',['matricule' => $participant->getSlug()]);
    }
}
