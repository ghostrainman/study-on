<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CourseControllerTest extends WebTestCase
{
    public function testIndexPageLoadsCourses(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
        $this->assertGreaterThan(0, $crawler->filter('table tbody tr')->count());
    }

    public function testShowCoursePage(): void
    {
        $client = static::createClient();
        $client->request('GET', '/courses/1');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Курс');
    }

    public function testShowCoursePageNotFound(): void
    {
        $client = static::createClient();
        $client->request('GET', '/courses/9999');

        $this->assertResponseStatusCodeSame(404);
    }

    public function testCreateCourseFormSuccess(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/new');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Сохранить')->form();
        $form['course[name]'] = 'Новый курс';
        $form['course[code]'] = 'new_code';
        $form['course[description]'] = 'Описание нового курса';

        $client->submit($form);

        $this->assertResponseRedirects('/courses');
        $client->followRedirect();

        $this->assertSelectorTextContains('table', 'Новый курс');
    }

    public function testCreateCourseFormValidation(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/new');

        $form = $crawler->selectButton('Сохранить')->form();
        $form['course[name]'] = '';
        $form['course[code]'] = '';
        $form['course[description]'] = '';

        $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorExists('.form-error-message');
    }

    public function testEditCourse(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/1/edit');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Сохранить')->form();
        $form['course[name]'] = 'Обновлённый курс';

        $client->submit($form);

        $this->assertResponseRedirects('/courses');
        $client->followRedirect();
        $this->assertSelectorTextContains('table', 'Обновлённый курс');
    }

    public function testCourseCodeMustBeUnique(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/new');

        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Сохранить')->form();

        $form['course[name]'] = 'Дубликат курса';
        $form['course[code]'] = 'symfony_course';
        $form['course[description]'] = 'Попытка дубликата';

        $client->submit($form);

        $this->assertResponseStatusCodeSame(422);
        $this->assertSelectorExists('.form-error-message');
        $this->assertSelectorTextContains('.form-error-message', 'Курс с таким символьным кодом уже существует.');
    }

    public function testDeleteCourse(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/courses/1');

        $form = $crawler->filter('.del-form');
        $token = $form->filter('input[name="_token"]')->attr('value');

        $client->request('POST', '/courses/1', [
            '_token' => $token,
        ]);

        $this->assertResponseRedirects('/courses');
    }
}
