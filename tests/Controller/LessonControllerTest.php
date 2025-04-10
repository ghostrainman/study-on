<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LessonControllerTest extends WebTestCase
{
    public function testShowLesson(): void
    {
        $client = static::createClient();
        $client->request('GET', '/lessons/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }

    public function testCreateLessonFormSuccess(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/lessons/new/1');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Сохранить')->form();
        $form['lesson[name]'] = 'Новый урок';
        $form['lesson[content]'] = 'Контент урока';
        $form['lesson[number]'] = 99;

        $client->submit($form);

        $this->assertResponseRedirects('/courses/1');
        $client->followRedirect();
        $this->assertSelectorTextContains('ul', 'Новый урок');
    }

    public function testCreateLessonFormValidation(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/lessons/new/1');

        $form = $crawler->selectButton('Сохранить')->form();
        $form['lesson[name]'] = '';
        $form['lesson[content]'] = '';
        $form['lesson[number]'] = '';

        $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorExists('.form-error-message');
    }

    public function testEditLesson(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/lessons/1/edit');

        $form = $crawler->selectButton('Сохранить')->form();
        $form['lesson[name]'] = 'Обновлённый урок';

        $client->submit($form);

        $this->assertResponseRedirects('/courses/1');
    }

    public function testDeleteLesson(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/lessons/1');

        $form = $crawler->filter('form')->form();
        $client->submit($form);

        $this->assertResponseRedirects('/courses/1');
    }
}
