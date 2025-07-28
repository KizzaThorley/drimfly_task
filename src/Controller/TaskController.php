<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class TaskController extends AbstractController
{
    #[Route('/tasks')]
    public function home() : Response
    {
     return $this->render('tasks/homepage.html.twig');
    }

}