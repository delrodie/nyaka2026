<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\List\Adhesion;
use App\Entity\Main\Grade as GradeMain;
use App\Entity\Main\Participant;
use App\Entity\Main\Section as SectionMain;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Uid\Uuid;

#[Route('/api/inscription')]
class ApiInscriptionController extends AbstractController
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    /**
     * Recherche une adhésion dans la base de données LIST
     */
    #[Route('/', name: 'api_inscription_recherche', methods: ['GET'])]
    public function recherche(Request $request, ManagerRegistry $doctrine): JsonResponse
    {
        // On récupère le gestionnaire d'entité spécifique pour la base LIST
        $em = $doctrine->getManager('list_manager');
        $repository = $em->getRepository(Adhesion::class);

        $mode = $request->query->get('mode');
        $results = [];

        if ($mode === 'id') {
            $matricule = $request->query->get('matricule');
            $adhesion = $repository->findOneBy(['matricule' => $matricule]);
            if ($adhesion) {
                $results[] = $this->serializeAdhesion($adhesion);
            }
        } else {
            $nom = $request->query->get('nom');
            $doyenne = $request->query->get('doyenne'); dump($doyenne);

//            $adhesions = $repository->findBy(['nomPrenoms' => $nom]);
            $adhesions = $repository->findByDoyenneAndMembre($nom, $doyenne);
            foreach ($adhesions as $adhesion) {
                $results[] = $this->serializeAdhesion($adhesion);
            }
        }
        dump($results);

        return $this->json($results);
    }

    #[Route('/', name:'api_participant_invite', methods: ['POST'])]
    public function invite(Request $request)
    {
        $emMain = $this->doctrine->getManager('default');

        $data = json_decode($request->getContent(), true);
        dump($data);

        $reqSection = $data['nouveau[section]'];
        $reqNomPrenoms = $data['nouveau[nomPrenoms]'];
        $reqGenre = $data['nouveau[genre]'];
        $reqAge = $data['nouveau[age]'];
        $reqGrade = $data['nouveau[grade]'];
        $reqTraitement = $data['nouveau[traitement]'];
        $reqDeclarantNom = $data['nouveau[declarantNom]'];
        $reqDeclarantContact = $data['nouveau[declarantContact]'];
        $reqProfil = $data['profil'];
        $reqTailleBenjamin = $data['tailleBenjamin'];
        $reqTailleAine = $data['tailleAine'];
        $reqTailleAA = $data['tailleAA'];
        $reqTailleAP = $data['tailleAP'];
        $reqMontant = $data['montant'];

        if ($reqTailleBenjamin) $taille = $reqTailleBenjamin;
        elseif ($reqTailleAine) $taille = $reqTailleAine;
        elseif ($reqTailleAA) $taille = $reqTailleAA;
        elseif ($reqTailleAP) $taille = $reqTailleAP;
        else $taille = "ND";

        $section = $emMain->getRepository(SectionMain::class)->findOneBy([
            'id' => (int) $reqSection,
        ]);

        $grade = $emMain->getRepository(GradeMain::class)->findOneBy([
            'id' => (int) $reqGrade,
        ]);

        try {
            // Sauvegarde des données
            $participant = new Participant();
            $participant->setSection($section);
            $participant->setGrade($grade);
            $participant->setSlug($this->generateMatriculeAndSlug()['slug']);
            $participant->setNomPrenoms($reqNomPrenoms);
            $participant->setTaille($taille);
            $participant->setTraitement($reqTraitement);
            $participant->setDeclarantNom($reqDeclarantNom);
            $participant->setDeclarantContact($reqDeclarantContact);
            $participant->setMontant((int) $reqMontant);
            $participant->setProfil($reqProfil);
            $participant->setGenre($reqGenre);
            $participant->setAge((int) $reqAge);
            $participant->setMatricule($this->generateMatriculeAndSlug()['matricule']);

            $emMain->persist($participant);
            $emMain->flush();

            return $this->json([
                'success' => true,
                'montant' => $participant->getMontant(),
                'matricule' => $participant->getSlug(),
                'id' => $participant->getId()
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur technique : ' . $e->getMessage()
            ], 500);
        }

        return $this->json([
            'success' => true,
            'montant' => 100,
            'matricule' => 000,
            'id' => 1
        ]);
    }

    #[Route('/{id}', name: 'api_participant_save', methods: ['POST'])]
    public function save(Request $request, Adhesion $adhesion): JsonResponse
    {
        $emMain = $this->doctrine->getManager('default');

        $data = json_decode($request->getContent(), true);

        $reqProfil = $data['profil'] ?? $request->request->get('profil');
        $reqTailleBenjamin = $data['tailleBenjamin'] ?? $request->request->get('tailleBenjamin');
        $reqTailleAine = $data['tailleAine'] ?? $request->request->get('tailleAine');
        $reqTailleAA = $data['tailleAA'] ?? $request->request->get('tailleAA');
        $reqTailleAP = $data['tailleAP'] ?? $request->request->get('tailleAP');
        $reqTraitement = $data['traitement'] ?? $request->request->get('traitement');
        $reqDeclarantNom = $data['declarantNom'] ?? $request->request->get('declarantNom');
        $reqDeclarantContact = $data['declarantContact'] ?? $request->request->get('declarantContact');
        $reqParticipant = $data['participant'] ?? $request->request->get('participant');
        $reqMontant = $data['montant'] ?? $request->request->get('montant');

        if ($reqTailleBenjamin) $taille = $reqTailleBenjamin;
        elseif ($reqTailleAine) $taille = $reqTailleAine;
        elseif ($reqTailleAA) $taille = $reqTailleAA;
        elseif ($reqTailleAP) $taille = $reqTailleAP;
        else $taille = "ND";

        //$taille = $reqTailleBenjamin ?? $reqTailleAine ?? $reqTailleAA ?? $reqTailleAP ?? "ND";

        //dump($taille);

        $section = $emMain->getRepository(SectionMain::class)->findOneBy([
            'uuid' => $adhesion->getSection()->getId(),
        ]);

        $grade = $emMain->getRepository(GradeMain::class)->findOneBy([
            'uuid' => $adhesion->getGrade()->getId(),
        ]);

        try{
            // Sauvegarde des données
            $participant = new Participant();
            $participant->setSection($section);
            $participant->setGrade($grade);
            $participant->setSlug($adhesion->getId());
            $participant->setNomPrenoms($adhesion->getNomPrenoms());
            $participant->setTaille($taille);
            $participant->setTraitement($reqTraitement);
            $participant->setDeclarantNom($reqDeclarantNom);
            $participant->setDeclarantContact($reqDeclarantContact);
            $participant->setMontant((int) $reqMontant);
            $participant->setProfil($reqProfil);
            $participant->setGenre($adhesion->getGenre());
            $participant->setAge($adhesion->getAge());
            $participant->setMatricule($adhesion->getMatricule());

            $emMain->persist($participant);
            $emMain->flush();

            return $this->json([
                'success' => true,
                'montant' => $participant->getMontant(),
                'matricule' => $participant->getSlug(),
                'id' => $participant->getId()
            ]);

        } catch (\Exception $e) {
            return $this->json([
                'success' => false,
                'message' => 'Erreur technique : ' . $e->getMessage()
            ], 500);
        }

    }
    /**
     * Transforme l'entité en tableau pour la réponse JSON
     */
    private function serializeAdhesion(Adhesion $adhesion): array
    {
        return [
            'id' => $adhesion->getId(),
            'matricule' => $adhesion->getMatricule(),
            'nomPrenoms' => $adhesion->getNomPrenoms(),
            'sectionId' => $adhesion->getSection() ? $adhesion->getSection()->getId() : null,
            'section' => $adhesion->getSection() ? $adhesion->getSection()->getNom() : null,
            'gradeId' => $adhesion->getGrade() ? $adhesion->getGrade()->getId() : null,
            'grade' => $adhesion->getGrade() ? $adhesion->getGrade()->getNom() : null,
            'doyenneId' => $adhesion->getSection()?->getDoyenne()?->getId(),
            'doyenne' => $adhesion->getSection()?->getDoyenne()?->getNom(),
        ];
    }

    protected function generateMatriculeAndSlug(): array
    {
        $emMain = $this->doctrine->getManager('default');

        // Slug
        $uuid = Uuid::v4();
        $slug = $uuid->toRfc4122();

        // Matricule
        $prefix = "CVAV-INV-2026-";

        do{
            $random = str_pad((string)random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $matricule = $prefix . $random;
        } while($emMain->getRepository(Participant::class)->findOneBy(['matricule' => $matricule]));

        return [
            'matricule' => $matricule,
            'slug' => $slug
        ];
    }
}
