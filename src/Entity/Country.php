<?php

declare(strict_types=1);

namespace App\Entity;

class Country
{
    /**
     * @param list<string> $borders
     * */
    public function __construct(
        public readonly string $name,
        public readonly array $borders
    ) {
    }
}
