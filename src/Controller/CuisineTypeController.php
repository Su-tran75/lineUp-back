<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CuisineTypeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CuisineTypeController extends AbstractController
{
    /**
     * Get all cuisineTypes
     * 
     * @Route("/api/cuisineTypes", name="api_cuisineTypes_get", methods={"GET"})
     */
    public function getAllCuisineType(CuisineTypeRepository $cuisineTypetRepository): Response
    {
        $cuisineType = $cuisineTypetRepository->findAll();
        
        return $this->json($cuisineType, Response::HTTP_OK, [], ['groups' => 'cuisineTypes_get']);
    }

    /**
     * Get one cuisineTypes
     * 
     * @Route("/api/cuisineTypes/{id}", name="api_cuisineTypes_get_one", methods={"GET"})
     */
    public function getOneCuisineType(CuisineTypeRepository $cuisineTypeRepository, $id): Response
    {
        $oneCuisineTypes = $cuisineTypeRepository->find($id);

        return $this->json($oneCuisineTypes, Response::HTTP_OK, [], ['groups' => 'cuisineTypes_get']);
    }
}
