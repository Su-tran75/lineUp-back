<?php

namespace App\EventListener;

use App\Entity\User;
use App\Repository\TicketRepository;
use Symfony\Component\Security\Core\User\UserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;

class AuthenticationSuccessListener
{
    private $ticketRepository;
    public function __construct(TicketRepository $ticketRepository)
    {
        $this->ticketRepository = $ticketRepository;
    }

    /**
     * Used for send back more informations when an user login
     * 
     * @param AuthenticationSuccessEvent $event
     */
    public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
    {
        $data = $event->getData();
        /** @var User $user */
        $user = $event->getUser();

        if (!$user instanceof UserInterface) {
            return;
        }

        // Check if the User have a restaurant. If not, return null
        if ($user->getRestaurant() === null) {
            $restaurantId = null;
        }

        else {
            $restaurantId = $user->getRestaurant()->getId();
            }


        $data['data'] = array(
        'id' => $user->getId(),
        'email' => $user->getUsername(),
        'firstname' => $user->getFirstname(),
        'lastname' => $user->getLastname(),      
        'tickets' => $this->ticketRepository->tickets($user), 
        'restaurantId' => $restaurantId,    
        'phoneNumber' => $user->getPhoneNumber(),
        'avatar' => $user->getAvatar(),
        'roles' => $user->getRoles(),
        'current_ticket' => $user->getCurrentTicket(),
     );
        $event->setData($data);
    }
}
