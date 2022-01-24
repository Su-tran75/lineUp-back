<?php

namespace App\Controller;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TicketController extends AbstractController
{
    /**
     * Get all tickets
     * 
     * @Route("/api/tickets", name="api_tickets_get", methods={"GET"})
     */
    public function getAll(TicketRepository $ticketRepository): Response
    {
        $tickets = $ticketRepository->findAll();
        
        return $this->json($tickets, Response::HTTP_OK, [], ['groups' => 'tickets_get']);
    }

    /**
     * Get one ticket
     * 
     * @Route("/api/tickets/{id}", name="api_tickets_get_one", methods={"GET"})
     */
    public function getOne(TicketRepository $ticketRepository, $id): Response
    {
        $oneticket = $ticketRepository->find($id);

        return $this->json($oneticket, Response::HTTP_OK, [], ['groups' => 'tickets_get']);
    }


    /**
     * Create ticket
     * 
     * @Route("/api/tickets", name="api_tickets_post", methods={"POST"})
     */
    public function addTicket(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        // Our JSON which is in the body
        $jsonContent = $request->getContent();

        // We deserialize JSON
        // => we transform the JSON into a ticket entity
        // @link https://symfony.com/doc/current/components/serializer.html
        $ticket = $serializer->deserialize($jsonContent, Ticket::class, 'json');
        // If the entity is valid, it is saved
        $entityManager->persist($ticket);
        $entityManager->flush();
        
        // This method return a JSON response
        return $this->json($ticket, Response::HTTP_OK, [], ['groups' => 'tickets_get']);
    }

    /**
     * Edit ticket (PATCH)
     * 
     * @Route("/api/tickets/{id}", name="api_tickets_patch", methods={"PATCH"})
     */
    public function patchticket(Ticket $ticket, EntityManagerInterface $em, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        // 404 ?
        if ($ticket === null) {
            // On retourne un message JSON + un statut 404
            return $this->json(['error' => 'Ticket non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $request->getContent();

        $serializer->deserialize(
            $jsonContent,
            Ticket::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $ticket]
        );

        $em->flush();

        return $this->json(['message' => 'Ticket modifié.'], Response::HTTP_OK);
    } 
}

