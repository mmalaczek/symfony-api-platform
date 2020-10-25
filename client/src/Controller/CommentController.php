<?php

namespace App\Controller;

use App\Form\Type\CommentType;
use App\Form\Type\SearchType;
use App\Model\Comment;
use App\Model\Search;
use App\Pagination\Pagination;
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
     * @param Pagination $pagination
     * @return Response
     * @throws TransportExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     */
    public function index(Request $request, Pagination $pagination): Response
    {
        $page = $request->query->getInt('page', 1);
        $nick = null;
        $comments = [];
        $totalItems = 0;

        $form = $this->createForm(SearchType::class, new Search());
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $nick = $form->getData()->getSearch();
        }

        try {
            $comments = $this->clientApi->getComments($page, $nick);
            if (!empty($comments)) {
                $totalItems = $comments['hydra:totalItems'];
                $comments = $comments['hydra:member'];
            }
        } catch (\Exception $e) {
            $this->addFlash('error', 'Błąd z połączeniem z API: '.$e->getMessage());
        }

        return $this->render('comment/index.html.twig', [
            'form' => $form->createView(),
            'comments' => $comments,
            'totalPages' => $pagination->totalPages($totalItems)
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
            try {
                $this->clientApi->addComment($form->getData());
                $this->addFlash('success', 'Komentarz został dodany.');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Błąd podczas dodawania nowego komentarza: '.$e->getMessage());
            }
            return $this->redirectToRoute('comment_index');
        }

        return $this->render('comment/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}
