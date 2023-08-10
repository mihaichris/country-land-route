<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\CountryRouter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RouteController extends AbstractController
{
    public function __construct(private readonly CountryRouter $countryRouter)
    {
    }

    #[Route('/route/{origin}/{destination}', name: 'app_route')]
    public function index(string $origin, string $destination): Response
    {
        $route = $this->countryRouter->route($origin, $destination);
        if (null === $route) {
            return new Response('No land crossing found', 400);
        }

        return new JsonResponse(['route' => $route]);
    }
}
