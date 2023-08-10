<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class RouteControllerTest extends WebTestCase
{
    public function testRouteBetweenTwoClosingCountries(): void
    {
        $client = static::createClient();
        $client->request('GET', '/route/CZE/ITA');
        $expected = ['route' => ['CZE', 'AUT', 'ITA']];
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame($expected, $actual);
    }

    public function testRouteBetweenTwoFarCountries(): void
    {
        $client = static::createClient();
        $client->request('GET', '/route/LSO/MYS');
        $expected = ['route' => ["LSO", "ZAF", "BWA", "ZMB", "COD", "CAF", "SDN", "EGY", "ISR", "JOR", "IRQ", "IRN", "AFG", "CHN", "MMR", "THA", "MYS"]];
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame($expected, $actual);
    }

    public function testRouteBetweenCountriesOnIsland(): void
    {
        $client = static::createClient();
        $client->request('GET', '/route/GBR/IRL');
        $expected = ['route' => ["GBR", "IRL"]];
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame($expected, $actual);
    }

    public function testRouteBetweenSameCountryOnIsland(): void
    {
        $client = static::createClient();
        $client->request('GET', '/route/CZE/CZE');
        $expected = ['route' => ["CZE"]];
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame($expected, $actual);
    }

    public function testRouteBetweenSameCountryLeaveIsland(): void
    {
        $client = static::createClient();
        $client->request('GET', '/route/GRL/GRL');
        $expected = ['route' => ["GRL"]];
        $actual = json_decode($client->getResponse()->getContent(), true);
        $this->assertSame($expected, $actual);
    }

    public function testNoRouteBetweenCountriesReturnBadRequestStatusCode(): void
    {
        $client = static::createClient();
        $client->request('GET', '/route/GRL/ITA');
        $this->assertResponseStatusCodeSame(400);
    }

    public function testNoRouteBetweenCountriesReturnBadRequestMessage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/route/GRL/ITA');
        $this->assertSame('No land crossing found', $client->getResponse()->getContent());
    }

    public function testRouteBetweenInvalidCountryCodes(): void
    {
        $client = static::createClient();
        $client->request('GET', '/route/AAAAAA/AAAAAA');
        $this->assertStringContainsString('Country not found', $client->getResponse()->getContent());
    }
}
