<?php

namespace App\Controller;

use App\Form\Type\CommentType;
use App\Form\Type\SearchType;
use App\Model\Comment;
use App\Service\ClientApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractController
{
    /**
     * @var ClientApi
     */
    protected $clientApi;

    /**
     * CommentController constructor.
     * @param ClientApi $clientApi
     */
    public function __construct(ClientApi $clientApi)
    {
        $this->clientApi = $clientApi;
    }

    /**
     * @param Request $request
     * @return Response
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    public function index(Request $request): Response
    {
        $comments = $this->clientApi->getComments();

        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/index.html.twig', [
            'form' => $form->createView(),
            'comments' => $comments
        ]);
    }

    /**
     * @param Request $request
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function new(Request $request): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->clientApi->addComment($form->getData());
            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
