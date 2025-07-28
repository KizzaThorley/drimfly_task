<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class TaskController 
{
    #[Route('/tasks')]
    public function home() : Response
    {
     return new Response('hello world');
    }

}