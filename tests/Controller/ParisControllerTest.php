<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ParisControllerTest extends WebTestCase{

    public function testListingParis(){

        $client = static::createClient();

        $client->request('GET', '/paris');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testListingMatch(){

        $client = static::createClient();

        $client->request('GET', '/match');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }
}