<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Countries;
use App\Entity\Country;

class CountryRepository
{
    public function getCountries(): Countries
    {
        $countries = new Countries();
        $countriesData = json_decode(file_get_contents('https://raw.githubusercontent.com/mledoze/countries/master/countries.json'), true);
        foreach ($countriesData as $countryData) {
            $countries[$countryData['cca3']] =  new Country($countryData['cca3'], $countryData['borders']);
        }
        return $countries;
    }
}
