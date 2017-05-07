<?php

namespace BookBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function testFirst()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/first');
    }

}
