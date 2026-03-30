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
use Symfony\Component\HttpFoundation\Response;
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
        try {
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
                $doyenne = $request->query->get('doyenne');
                $adhesions = $repository->findByDoyenneAndMembre($nom, $doyenne);
                foreach ($adhesions as $adhesion) {
                    $results[] = $this->serializeAdhesion($adhesion);
                }
            }

            return $this->jsonResponse($results);

        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erreur technique : ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/', name: 'api_participant_invite', methods: ['POST'])]
    public function invite(Request $request): JsonResponse
    {
        try {
            $emMain = $this->doctrine->getManager('default');

            $data = json_decode($request->getContent(), true);

            if (!$data || !is_array($data)) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Corps de la requête invalide ou vide.'
                ], Response::HTTP_BAD_REQUEST);
            }

            // ─── Lecture sécurisée des champs (null si absent) ───────────────────
            $reqSection          = $data['nouveau[section]']         ?? null;
            $reqNomPrenoms       = $data['nouveau[nomPrenoms]']      ?? null;
            $reqGenre            = $data['nouveau[genre]']           ?? null;
            $reqAge              = $data['nouveau[age]']             ?? null;
            $reqGrade            = $data['nouveau[grade]']           ?? null;
            $reqTraitement       = $data['nouveau[traitement]']      ?? null;
            $reqDeclarantNom     = $data['nouveau[declarantNom]']    ?? null;
            $reqDeclarantContact = $data['nouveau[declarantContact]'] ?? null;
            $reqProfil           = $data['profil']                   ?? null;
            $reqTailleBenjamin   = $data['tailleBenjamin']           ?? null;
            $reqTailleAine       = $data['tailleAine']               ?? null;
            $reqTailleAA         = $data['tailleAA']                 ?? null;
            $reqTailleAP         = $data['tailleAP']                 ?? null;
            $reqMontant          = $data['montant']                  ?? 0;

            // ─── Validation des champs obligatoires ──────────────────────────────
            $missing = [];
            if (!$reqNomPrenoms)       $missing[] = 'nomPrenoms';
            if (!$reqSection)          $missing[] = 'section';
            if (!$reqGrade)            $missing[] = 'grade';
            if (!$reqProfil)           $missing[] = 'profil';

            if (!empty($missing)) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Champs manquants : ' . implode(', ', $missing)
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $taille = $reqTailleBenjamin ?: ($reqTailleAine ?: ($reqTailleAA ?: ($reqTailleAP ?: 'ND')));

            $section = $emMain->getRepository(SectionMain::class)->findOneBy(['id' => (int) $reqSection]);
            $grade   = $emMain->getRepository(GradeMain::class)->findOneBy(['id' => (int) $reqGrade]);

            if (!$section) {
                return $this->jsonResponse(['success' => false, 'message' => 'Section introuvable.'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            if (!$grade) {
                return $this->jsonResponse(['success' => false, 'message' => 'Grade introuvable.'], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

            $ids = $this->generateMatriculeAndSlug();

            $participant = new Participant();
            $participant->setSection($section);
            $participant->setGrade($grade);
            $participant->setSlug($ids['slug']);
            $participant->setMatricule($ids['matricule']);
            $participant->setNomPrenoms($reqNomPrenoms);
            $participant->setTaille($taille);
            $participant->setTraitement($reqTraitement);
            $participant->setDeclarantNom($reqDeclarantNom);
            $participant->setDeclarantContact($reqDeclarantContact);
            $participant->setMontant((int) $reqMontant);
            $participant->setProfil($reqProfil);
            $participant->setGenre($reqGenre);
            $participant->setAge((int) $reqAge);

            $emMain->persist($participant);
            $emMain->flush();

            return $this->jsonResponse([
                'success'  => true,
                'montant'  => $participant->getMontant(),
                'matricule' => $participant->getSlug(),
                'id'       => $participant->getId()
            ]);

        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erreur technique : ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    #[Route('/{id}', name: 'api_participant_save', methods: ['POST'])]
    public function save(Request $request, Adhesion $adhesion): JsonResponse
    {
        try {
            $emMain = $this->doctrine->getManager('default');

            $data = json_decode($request->getContent(), true) ?? [];

            $reqProfil           = $data['profil']           ?? $request->request->get('profil');
            $reqTailleBenjamin   = $data['tailleBenjamin']   ?? $request->request->get('tailleBenjamin');
            $reqTailleAine       = $data['tailleAine']       ?? $request->request->get('tailleAine');
            $reqTailleAA         = $data['tailleAA']         ?? $request->request->get('tailleAA');
            $reqTailleAP         = $data['tailleAP']         ?? $request->request->get('tailleAP');
            $reqTraitement       = $data['traitement']       ?? $request->request->get('traitement');
            $reqDeclarantNom     = $data['declarantNom']     ?? $request->request->get('declarantNom');
            $reqDeclarantContact = $data['declarantContact'] ?? $request->request->get('declarantContact');
            $reqMontant          = $data['montant']          ?? $request->request->get('montant', 0);

            $taille = $reqTailleBenjamin ?: ($reqTailleAine ?: ($reqTailleAA ?: ($reqTailleAP ?: 'ND')));

            $section = $emMain->getRepository(SectionMain::class)->findOneBy([
                'uuid' => $adhesion->getSection()->getId(),
            ]);
            $grade = $emMain->getRepository(GradeMain::class)->findOneBy([
                'uuid' => $adhesion->getGrade()->getId(),
            ]);

            if (!$section || !$grade) {
                return $this->jsonResponse([
                    'success' => false,
                    'message' => 'Section ou grade introuvable dans la base principale.'
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }

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

            return $this->jsonResponse([
                'success'  => true,
                'montant'  => $participant->getMontant(),
                'matricule' => $participant->getSlug(),
                'id'       => $participant->getId()
            ]);

        } catch (\Exception $e) {
            return $this->jsonResponse([
                'success' => false,
                'message' => 'Erreur technique : ' . $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────────

    /**
     * Wrapper JsonResponse qui force toujours le Content-Type application/json.
     * Évite que Symfony en prod renvoie du HTML à la place.
     */
    private function jsonResponse(mixed $data, int $status = Response::HTTP_OK): JsonResponse
    {
        $response = new JsonResponse($data, $status);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

    private function serializeAdhesion(Adhesion $adhesion): array
    {
        return [
            'id'        => $adhesion->getId(),
            'matricule' => $adhesion->getMatricule(),
            'nomPrenoms' => $adhesion->getNomPrenoms(),
            'sectionId' => $adhesion->getSection()?->getId(),
            'section'   => $adhesion->getSection()?->getNom(),
            'gradeId'   => $adhesion->getGrade()?->getId(),
            'grade'     => $adhesion->getGrade()?->getNom(),
            'doyenneId' => $adhesion->getSection()?->getDoyenne()?->getId(),
            'doyenne'   => $adhesion->getSection()?->getDoyenne()?->getNom(),
        ];
    }

    protected function generateMatriculeAndSlug(): array
    {
        $emMain = $this->doctrine->getManager('default');

        do {
            $uuid = Uuid::v4();
            $slug = $uuid->toRfc4122();
        } while ($emMain->getRepository(Participant::class)->findOneBy(['slug' => $slug]));

        $prefix = "CVAV-INV-2026-";
        do {
            $random = str_pad((string) random_int(0, 99999), 5, '0', STR_PAD_LEFT);
            $matricule = $prefix . $random;
        } while ($emMain->getRepository(Participant::class)->findOneBy(['matricule' => $matricule]));

        return ['matricule' => $matricule, 'slug' => $slug];
    }
}
