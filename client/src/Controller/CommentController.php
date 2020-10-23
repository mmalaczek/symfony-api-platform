<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [

        ]);
    }

    public function new(Request $request)
    {
        return $this->render('comment/new.html.twig', [

        ]);
    }

}
