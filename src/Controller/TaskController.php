<?php

namespace App\Controller;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    #[Route('/tasks', name: 'task_list', methods: ['GET'])]
    public function list(): Response
    {
        $tasks = $this->em->getRepository(Task::class)->findBy(['deletedAt' => null]);

        return $this->render('tasks/homepage.html.twig', ['tasks' => $tasks]);
    }

    #[Route('/tasks', name: 'task_add', methods: ['POST'])]
    public function add(Request $request): RedirectResponse
    {
        $title = $request->request->get('title');
        if (!$title) {
            return $this->redirectToRoute('task_list');
        }
    
        $task = new Task();
        $task->setTitle($title);
        $task->setIsDone(false);
    
        $this->em->persist($task);
        $this->em->flush();
    
        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/toggle', name: 'task_toggle', methods: ['POST'])]
    public function toggle(int $id): RedirectResponse
    {
        $task = $this->em->getRepository(Task::class)->find($id);

        if (!$task || $task->getDeletedAt() !== null) {
            $this->addFlash('error', 'Task not found');
            return $this->redirectToRoute('task_list');
        }

        $task->setIsDone(!$task->getIsDone());
        $this->em->flush();

        return $this->redirectToRoute('task_list');
    }

    #[Route('/tasks/{id}/delete', name: 'task_delete', methods: ['POST'])]
    public function delete(int $id): RedirectResponse
    {
        $task = $this->em->getRepository(Task::class)->find($id);

        if (!$task || $task->getDeletedAt() !== null) {
            $this->addFlash('error', 'Task not found');
            return $this->redirectToRoute('task_list');
        }

        $task->setDeletedAt(new \DateTime());
        $this->em->flush();

        return $this->redirectToRoute('task_list');
    }
}
