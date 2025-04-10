<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testRootRedirectsToCoursesAndLoadsSuccessfully(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseRedirects('/courses');

        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }
}
