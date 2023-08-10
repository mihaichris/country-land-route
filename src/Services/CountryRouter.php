<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Country;
use App\Repository\CountryRepository;
use SplQueue;
use WeakMap;

class CountryRouter
{
    public function __construct(private readonly CountryRepository $countryRepository)
    {
    }

    public function route(string $originCountryCode, string $destinationCountryCode)
    {
        $countries = $this->countryRepository->getCountries();
        $originCountry = $countries->findCountryByCode($originCountryCode);
        $destinationCountry = $countries->findCountryByCode($destinationCountryCode);

        $visited = new WeakMap();
        $previous = new WeakMap();

        $currentCountry = $originCountry;

        $queue = new SplQueue();
        $queue->enqueue($currentCountry);

        $visited[$currentCountry] = true;

        while (!$queue->isEmpty()) {
            /** @var Country $currentCountry */
            $currentCountry = $queue->dequeue();

            if ($currentCountry === $destinationCountry) {
                break;
            } else {
                foreach ($currentCountry->borders as $border) {
                    $neighbourCountry = $countries->findCountryByCode($border);
                    if (!isset($visited[$neighbourCountry])) {
                        $queue->enqueue($neighbourCountry);
                        $visited[$neighbourCountry] = true;
                        $previous[$neighbourCountry] = $currentCountry;
                        if ($neighbourCountry === $destinationCountry) {
                            $currentCountry = $neighbourCountry;
                            break 2;
                        }
                    }
                }
            }
        }

        if ($currentCountry !== $destinationCountry) {
            return null;
        }

        $route = [$destinationCountry->name];
        $node = $destinationCountry;
        while (isset($previous[$node])) {
            $node = $previous->offsetGet($node);
            $route[] = $node->name;
        }

        return array_reverse($route);
    }
}
