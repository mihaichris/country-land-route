<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Countries;
use App\Entity\Country;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class CountryRepository
{
    public function __construct(private readonly HttpClientInterface $httpClient)
    {
    }

    public function getCountries(): Countries
    {
        $countries = new Countries();
        $response = $this->httpClient->request('GET', 'https://raw.githubusercontent.com/mledoze/countries/master/countries.json');
        $countriesData = $response->toArray();
        foreach ($countriesData as $countryData) {
            $countries[$countryData['cca3']] =  new Country($countryData['cca3'], $countryData['borders']);
        }
        return $countries;
    }
}
