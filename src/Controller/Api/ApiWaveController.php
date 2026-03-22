<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Entity\Main\Participant;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[Route('/api/wave')]
class ApiWaveController extends AbstractController
{
    public function __construct(
        private HttpClientInterface $httpClient,
        private SerializerInterface $serializer,
        private ManagerRegistry $doctrine

    )
    {
    }

    #[Route('/checkout', name:'api_wave_checkout', methods: ['POST'])]
    public function checkout(Request $request): Response
    {
        $emMain = $this->doctrine->getManager('default');
        $data = json_decode($request->getContent(), true);



        try {
            $response = $this->httpClient->request(
                'POST',
                'https://api.wave.com/v1/checkout/sessions',[
                    'json' => $data,
                    'headers' => [
                        'Authorization' => 'Bearer ' . $this->getParameter('wave_api_key'),
                        'Content-Type' => 'application/json',
                    ]
                ]
            );

            if ($response->getStatusCode() === 200){
                $content = json_decode($response->getContent());
                $matricule = basename($data['success_url']);

                $participant = $emMain->getRepository(Participant::class)->findOneBy([
                    'slug' => $matricule
                ]);

                if ($participant){
                    $participant->setWaveId($content->id);
                    $participant->setWaveCheckoutStatus($content->checkout_status);
                    $participant->setWaveClientReference($content->client_reference);
                    $participant->setWavePaymentStatus($content->payment_status);
                    $participant->setWaveWhenCompleted($content->when_completed);
                    $participant->setWaveWhenCreated($content->when_created);

                    $emMain->flush();
                }
            }
            return $this->json($response);

        } catch (\Exception $exception){
            return new JsonResponse(['error' => $exception->getMessage()], 500);
        }
    }
}
