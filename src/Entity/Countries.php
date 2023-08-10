<?php

declare(strict_types=1);

namespace App\Entity;

use ArrayObject;
use Exception;

class Countries extends ArrayObject
{
    public function findCountryByCode(string $code): Country
    {
        $countries = $this->getArrayCopy();
        if (!isset($countries[$code])) {
            throw new Exception('Country not found');
        }

        return $countries[$code];
    }
}
