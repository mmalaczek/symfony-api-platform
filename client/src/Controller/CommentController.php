<?php

namespace App\Controller;

use App\Form\Type\CommentType;
use App\Model\Comment;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [

        ]);
    }

    public function new(Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
