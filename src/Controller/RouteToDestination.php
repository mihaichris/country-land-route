<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\CountryRouter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/route/{origin}/{destination}')]
class RouteToDestination extends AbstractController
{
    public function __construct(private readonly CountryRouter $countryRouter)
    {
    }

    public function __invoke(string $origin, string $destination): Response
    {
        $route = $this->countryRouter->route($origin, $destination);
        if (null === $route) {
            return new Response('No land crossing found', 400);
        }

        return new JsonResponse(['route' => $route]);
    }
}
