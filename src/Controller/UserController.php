<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    /**
     * Get all users
     * 
     * @Route("/api/users", name="api_users_get", methods={"GET"})
     */
    public function getAll(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        // dd($users);
        
        return $this->json($users, Response::HTTP_OK, [], ['groups' => 'users_get']);
    }

    /**
     * Get one user
     * 
     * @Route("/api/users/{id}", name="api_users_get_one", methods={"GET"})
     */
    public function getOne(UserRepository $userRepository, $id): Response
    {
        $oneUser = $userRepository->find($id);

        return $this->json($oneUser, Response::HTTP_OK, [], ['groups' => 'users_get']);
    }

    /**
     * Create user
     * 
     * @Route("/api/users", name="api_users_post", methods="POST")
     */
    public function addUser(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $jsonContent = $request->getContent();
        // @link https://symfony.com/doc/current/components/serializer.html
        $user = $serializer->deserialize($jsonContent, User::class, 'json');

        // Encod password
        $passwordToHash = $user->getPassword();
        $passwordEncoded = $passwordEncoder->encodePassword($user, $passwordToHash);
        $user->setPassword($passwordEncoded);

        // If valid, we save it
        $entityManager->persist($user);
        $entityManager->flush();

        // Sending back to the front
        return $this->json(
            $user,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('api_users_get_one', ['id' => $user->getId()])
            ],
        );
    }

    /**
     * Edit user (PATCH)
     * 
     * @Route("/api/users/{id}", name="api_users_patch", methods={"PATCH"})
     */
    public function editUser(User $user = null, Request $request, EntityManagerInterface $entityManager, SerializerInterface $serializer, ValidatorInterface $validator, UserPasswordEncoderInterface $encoder)
    {
        // if the user is not found, answer with message json and status code 404
        if ($user === null) {
            // We return a JSON message + a 404 status
            return $this->json(['error' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        // Take the old password encoded
        $oldPassword = $user->getPassword();

        // Here we recover what the front sends us 
        $jsonContent = $request->getContent();

        // We associate it with the user in the DB
        // So, if I don't get the password key in the json, I get the password stored in the DB (the encrypted one)
        // Otherwise I get the new password in clear with all the data of the user
        $updatedUser = $serializer->deserialize(
            $jsonContent, 
            User::class, 
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $user]
        );
           
        // if value in key "password" change, we go inside condition
        if( $updatedUser->getPassword() !== $oldPassword )
        {
            // take the new value of "password"
            $newPassword = $updatedUser->getPassword();

            // Overwrites the old password with the new password encoded
            $updatedUser->setPassword($encoder->encodePassword($updatedUser, $newPassword));
        }
        
        $entityManager->flush();

        return $this->json(['message' => 'Utilisateur modifié.'], Response::HTTP_OK);
    } 
   
    /**
     * Delete user
     * 
     * @Route("/api/users/{id}", name="api_users_delete", methods="DELETE")
     */
    public function delete(User $user = null, EntityManagerInterface $em)
    {
        // 404 ?
        if ($user === null) {
            // Sending back Json + error message
            return $this->json(['error' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($user);
        $em->flush();

        return $this->json(['message' => 'Utilisateur supprimé.'], Response::HTTP_OK);
    }

}
