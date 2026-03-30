<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\List\Adhesion;
use App\Entity\Main\Participant;
use App\Entity\Main\Participation;
use App\Form\NouveauType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/inscription')]
class InscriptionController extends AbstractController
{
    #[Route('/', name: 'app_inscription_recherche')]
    public function recherche(): Response
    {
        return $this->render('frontend/recherche.html.twig');
    }

    #[Route('/nouveau', name:'app_inscription_nouveau', methods: ['GET','POST'])]
    public function nouveau(Request $request, ManagerRegistry $doctrine): Response
    {
        $emMain = $doctrine->getManager('default');
        $tarifs = $emMain->getRepository(Participation::class)->findAll();

        $participant = new Participant();
        $form = $this->createForm(NouveauType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = json_decode($request->getContent(), true);
            dump($data);

            return $this->json('data');
        }

        // On prépare un petit tableau simple pour JS : [ "nom_du_grade" => montant ]
        $listeTarifs = [];
        foreach ($tarifs as $t) {
            if ($t->getGrade()) {
                $listeTarifs[strtolower($t->getGrade()->getNom())] = $t->getMontant();
            }
        }

        return $this->render('frontend/inscription_nouveau.html.twig',[
            'tarifs' => $listeTarifs,
            'participant' => $participant,
            'form' => $form
        ]);
    }

    #[Route('/resultats', name: 'app_inscription_resultats', methods: ['GET'])]
    public function resultats(Request $request, ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager('list_manager');
        $repository = $em->getRepository(Adhesion::class);

        $mode = $request->query->get('mode');
        $results = [];

        if ($mode === 'id') {
            $matricule = $request->query->get('matricule');
            $adhesion = $repository->findOneBy(['matricule' => $matricule]);
            if ($adhesion) {
                $results[] = $adhesion;
            }
        } else {
            $nom = $request->query->get('nom');
            $doyenneId = $request->query->get('doyenne');

            // Utilisation de votre méthode personnalisée du repository
            $results = $repository->findByDoyenneAndMembre($nom, $doyenneId);
        }

        // CAS 1 : Aucun résultat
        if (empty($results)) {
            $this->addFlash('warning', 'Aucun membre trouvé avec ces critères.');
            return $this->redirectToRoute('app_inscription_recherche');
        }

        // CAS 2 : Résultat unique -> Redirection directe vers la fiche membre
        if (count($results) === 1) {
            return $this->redirectToRoute('app_inscription_membre', ['id' => $results[0]->getId()]);
        }

        // CAS 3 : Résultats multiples -> Affichage de la liste de sélection
        return $this->render('frontend/recherche_liste.html.twig', [
            'adhesions' => $results
        ]);
    }

    #[Route('/membre/{id}', name: 'app_inscription_membre')]
    public function membre(Adhesion $adhesion, ManagerRegistry $doctrine): Response
    {
        $emMain = $doctrine->getManager('default');

        $tarifs = $emMain->getRepository(Participation::class)->findAll();

        // On prépare un petit tableau simple pour JS : [ "nom_du_grade" => montant ]
        $listeTarifs = [];
        foreach ($tarifs as $t) {
            if ($t->getGrade()) {
                $listeTarifs[strtolower($t->getGrade()->getNom())] = $t->getMontant();
            }
        }

        return $this->render('frontend/membre.html.twig', [
            'adhesion' => $adhesion,
            'tarifs' => $listeTarifs
        ]);
    }
}
