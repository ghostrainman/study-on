<?php

namespace App\DataFixtures;

use App\Entity\Course;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourseFixtures extends Fixture
{
    public const COURSES = [
        'symfony_course' => [
            'name' => 'Курс Symfony',
            'description' => 'В рамках практических занятий требуется разработать сервис онлайн-обучения StudyOn и биллинг-систему StudyOn.Billing для него. Пользователи могут регистрироваться в сервисе, оплачивать и проходить курсы, получая сертификаты по итогу прохождения курсов.',
        ],
        'bitrix_course' => [
            'name' => 'Курс Bitrix',
            'description' => 'Курс для начинающего разработчика на платформе Bitrix Framework.',
        ],
        'php_course' => [
            'name' => 'Курс PHP',
            'description' => 'С нуля освоите язык программирования PHP, получите помощь и советы от опытных экспертов, попрактикуетесь на реальных задачах. Напишете первый проект для портфолио.',
        ],
    ];
    public function load(ObjectManager $manager): void
    {
        foreach (self::COURSES as $code => $data) {
            $course = new Course();
            $course->setName($data['name']);
            $course->setCode($code);
            $course->setDescription($data['description']);
            $manager->persist($course);

            $this->addReference($code, $course);
        }

        $manager->flush();
    }
}
