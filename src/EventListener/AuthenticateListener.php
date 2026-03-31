<?php

namespace App\EventListener;

use App\Entity\Main\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\Security\Core\Event\AuthenticationSuccessEvent;
use Symfony\Component\Security\Core\User\UserInterface;

final class AuthenticateListener
{
    public function __construct(private ManagerRegistry $doctrine)
    {
    }

    #[AsEventListener(event: 'security.authentication.success')]
    public function onSecurityAuthenticationSuccess(AuthenticationSuccessEvent $event): void
    {
        $emMain = $this->doctrine->getManager('default');

        $user = $event->getAuthenticationToken()->getUser();
        if($user instanceof User) {
            $user->setConnexion((int) $user->getConnexion() + 1 );
            $user->setLastConnectedAt(new \DateTimeImmutable());

            $emMain->flush();
        }
    }
}
