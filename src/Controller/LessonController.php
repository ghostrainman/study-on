<?php

namespace App\Controller;

use App\Entity\Lesson;
use App\Entity\Course;
use App\Form\LessonType;
use App\Repository\LessonRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/lessons')]
final class LessonController extends AbstractController
{
    #[Route(name: 'app_lesson_index', methods: ['GET'])]
    public function index(LessonRepository $lessonRepository): Response
    {
        return $this->render('lesson/index.html.twig', [
            'lessons' => $lessonRepository->findAll(),
        ]);
    }

    #[Route('/new/{courseId}', name: 'app_lesson_new', methods: ['GET', 'POST'])]
    public function new(int $courseId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $course = $entityManager->getRepository(Course::class)->find($courseId);
        if (!$course) {
            throw $this->createNotFoundException('Курс не найден');
        }

        $lesson = new Lesson();
        $lesson->setCourse($course);

        $form = $this->createForm(LessonType::class, $lesson, [
            'course_id' => $course->getId()
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($lesson);
            $entityManager->flush();

            $course = $lesson->getCourse();
            if ($course) {
                return $this->redirectToRoute('app_course_show', [
                    'id' => $course->getId(),
                ], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('lesson/new.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
            'course' => $course,
        ]);
    }

    #[Route('/{id}', name: 'app_lesson_show', methods: ['GET'])]
    public function show(Lesson $lesson): Response
    {
        return $this->render('lesson/show.html.twig', [
            'lesson' => $lesson,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_lesson_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Lesson $lesson, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $course = $lesson->getCourse();
            if ($course) {
                return $this->redirectToRoute('app_course_show', [
                    'id' => $course->getId(),
                ], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->render('lesson/edit.html.twig', [
            'lesson' => $lesson,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_lesson_delete', methods: ['POST'])]
    public function delete(Request $request, Lesson $lesson, EntityManagerInterface $entityManager): Response
    {
        $course = $lesson->getCourse();

        if ($this->isCsrfTokenValid('delete' . $lesson->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($lesson);
            $entityManager->flush();
        }

        if ($course) {
            return $this->redirectToRoute('app_course_show', [
                'id' => $course->getId(),
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->redirectToRoute('app_course_index', [], Response::HTTP_SEE_OTHER);
    }
}
