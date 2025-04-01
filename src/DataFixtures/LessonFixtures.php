<?php

namespace App\DataFixtures;

use App\Entity\Course;
use App\Entity\Lesson;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
//use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class LessonFixtures extends Fixture
{
    public const LESSONS = [
        [
            'name' => 'Введение в Symfony',
            'content' => 'Изучаем основы Symfony.',
            'course' => 'symfony_course',
            'number' => 1
        ],
        [
            'name' => 'Роутинг в Symfony',
            'content' => 'Как работает маршрутизация?',
            'course' => 'symfony_course',
            'number' => 2
        ],
        [
            'name' => 'Тестирование в Symfony',
            'content' => 'Как писать тесты?',
            'course' => 'symfony_course',
            'number' => 3
        ],
        [
            'name' => 'Установка Bitrix',
            'content' => 'Как правильно установить Bitrix.',
            'course' => 'bitrix_course',
            'number' => 1
        ],
        [
            'name' => 'Компоненты Bitrix',
            'content' => 'Разбираемся с компонентами.',
            'course' => 'bitrix_course',
            'number' => 2
        ],
        [
            'name' => 'Шаблоны Bitrix',
            'content' => 'Настраиваем шаблоны сайта на Bitrix.',
            'course' => 'bitrix_course',
            'number' => 3
        ],
        [
            'name' => 'Переменные в PHP',
            'content' => 'Что такое переменные?',
            'course' => 'php_course',
            'number' => 1
        ],
        [
            'name' => 'Циклы в PHP',
            'content' => 'Разбираем for, while, foreach.',
            'course' => 'php_course',
            'number' => 2
        ],
        [
            'name' => 'Типы данных в PHP',
            'content' => 'Типы данных и для чего нужны?',
            'course' => 'php_course',
            'number' => 3
        ],
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::LESSONS as $data) {
            $lesson = new Lesson();
            $lesson->setName($data['name']);
            $lesson->setContent($data['content']);
            $lesson->setNumber($data['number']);

            // Получаем курс из фикстур
            $lesson->setCourse($this->getReference($data['course'], Course::class));

            $manager->persist($lesson);
        }

        $manager->flush();
    }
}
