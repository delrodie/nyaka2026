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

#[Route('/recu')]
class RecuController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private ManagerRegistry $doctrine
    )
    {
    }

    #[Route('/recherche/r', name: 'app_recu_recherche', methods: ['GET','POST'])]
    public function recherche(Request $request): Response
    {
        $emMain = $this->doctrine->getManager('default');
        $reqMatricule = $request->request->get('matricule'); //dd($reqMatricule);

        if ($reqMatricule) {

            $participant = $emMain->getRepository(Participant::class)->findOneBy(['matricule' => $reqMatricule]);


            if ($participant && $participant->getWaveCheckoutStatus() !== 'complete'){

                $wave = $this->wave($participant);
                if ($wave !== true) return $this->redirectToRoute('app_echec_recu',['slug' => $participant->getSlug()]);
            }

            return $this->render('frontend/recu_search.html.twig',[
                'participant' => $participant,
            ]);
        }

        return $this->render('frontend/recherche_recu.html.twig');

    }

    #[Route('/{matricule}', name: 'app_recu_show', methods: ['GET'])]
    public function show($matricule): Response
    {
        $emMain = $this->doctrine->getManager('default');

        $participant = $emMain->getRepository(Participant::class)->findOneBy([
            'slug' => $matricule
        ]);

        if ($participant && $participant->getWaveCheckoutStatus() !== 'complete'){

            $wave = $this->wave($participant);
            if ($wave !== true) return new Response ($wave);
        }
        return $this->render('frontend/recu_search.html.twig',[
            'participant' => $participant,
        ]);
    }


    public function wave($aspirant)
    {
        $emMain = $this->doctrine->getManager('default');
        $response = $this->httpClient->request(
            'GET',
            "https://api.wave.com/v1/checkout/sessions/{$aspirant->getWaveId()}",[
                'headers' => [
                    'Authorization' => 'Bearer '.$this->getParameter('wave_api_key'),
                ],
                'timeout' => 5
            ]
        );

        if ($response->getStatusCode() !== 200){
            return  "HTTP Error ".$response->getStatusCode();
        }

        $data = $response->toArray();

        if ($data['checkout_status'])
            $aspirant->setWaveCheckoutStatus($data['checkout_status']);
        if ($data['payment_status'])
            $aspirant->setWavePaymentStatus($data['payment_status']);
        if ($data['when_completed'])
            $aspirant->setWaveWhenCompleted($data['when_completed']);
        if ($data['transaction_id'])
            $aspirant->setWaveTransactionId($data['transaction_id']);

        $emMain->flush();

        return true;
    }
}
