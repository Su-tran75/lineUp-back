<?php

namespace App\Controller;

use App\Entity\Restaurant;
use App\Repository\TicketRepository;
use App\Repository\RestaurantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RestaurantController extends AbstractController
{
    /**
     * Get all restaurants
     * 
     * @Route("/api/restaurants", name="api_restaurants_get", methods={"GET"})
     */
    public function getAll(RestaurantRepository $restaurantRepository): Response
    {
        $restaurants = $restaurantRepository->findAll();
        
        return $this->json($restaurants, Response::HTTP_OK, [], ['groups' => 'restaurants_get']);
    }

    /**
     * Get one restaurant
     * 
     * @Route("/api/restaurants/{id}", name="api_restaurants_get_one", methods={"GET"})
     */
    public function getOne(RestaurantRepository $restaurantRepository, $id): Response
    {
        $oneRestaurant = $restaurantRepository->find($id);

        return $this->json($oneRestaurant, Response::HTTP_OK, [], ['groups' => 'restaurants_get']);
    }

    /**
     * Add one restaurant
     * 
     * @Route("/api/restaurants", name="api_restaurants_post", methods={"POST"})
     */
    public function addRestaurant(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        // Json in request body
        $jsonContent = $request->getContent();

        $restaurant = $serializer->deserialize($jsonContent, Restaurant::class, 'json');
       
        $entityManager->persist($restaurant);
        $entityManager->flush();

        // Sending Json on front
        return $this->json($restaurant, Response::HTTP_CREATED, [], ['groups' => 'restaurants_get'],
            [
                'Location' => $this->generateUrl('api_restaurants_get_one', ['id' => $restaurant->getId()])
            ],
        );
    }

    /**
     * Edit restaurant (PATCH)
     * 
     * @Route("/api/restaurants/{id}", name="api_retaurants_patch", methods={"PATCH"})
     */
    public function patchRestaurant(Restaurant $restaurant = null, EntityManagerInterface $entityManager, SerializerInterface $serializer, Request $request, ValidatorInterface $validator)
    {
        // 404
        if ($restaurant === null) {
            
            return $this->json(['error' => 'Restaurant not found.'], Response::HTTP_NOT_FOUND);
        }

        $jsonContent = $request->getContent();

        // @see https://symfony.com/doc/current/components/serializer.html#deserializing-in-an-existing-object
        $serializer->deserialize(
            $jsonContent,
            Restaurant::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $restaurant],
        );

        $entityManager->flush();

        return $this->json(['message' => 'Restaurant modified.'], Response::HTTP_OK);
    }

    /**
     * Delete restaurant
     * 
     * @Route("/api/restaurants/{id}", name="api_restaurants_delete", methods="DELETE")
     */
    public function delete(Restaurant $restaurant = null, EntityManagerInterface $em)
    {
        // 404
        if ($restaurant === null) {
            // Sending resutl on front + Error message
            return $this->json(['error' => 'Restaurant non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($restaurant);
        $em->flush();

        return $this->json(['message' => 'Restaurant supprimé.'], Response::HTTP_OK);
    }


    /**
    * Get tickets by restaurant
    * 
    * @Route("/api/tickets/restaurants/{id}", name="api_tickets_get_by_restaurant", methods={"GET"})
    */
    public function findTicketsByRestaurant(Restaurant $restaurant): Response
    {
        $tickets = $restaurant->getTicket();
        return $this->json($tickets, Response::HTTP_OK, [], ['groups' => 'tickets_get']);


        // pour montrer comment on ferait sans Symfony (enfin, surtout sans serializer !) :

        // $json = [];
        // foreach ($tickets as $ticket) {
        //     $json[] = [
        //         'id' => $ticket->getId(),
        //         'guest' => $ticket->getGuest()  // etc. => hyper relou à faire !
        //     ];
        // }
        // return $this->json($json);

    }
}

        