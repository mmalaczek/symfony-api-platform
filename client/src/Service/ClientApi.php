<?php

namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ClientApi
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * ClientApi constructor.
     * @param HttpClientInterface $client
     */
    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    /**
     * @return mixed
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getComments()
    {
        $response = $this->client->request(
            'GET',
            'http://127.0.0.1:8000/api/comments?page=1'
        );

        return $response->toArray()['hydra:member'];
    }

    /**
     * @param $formData
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function addComment($formData): void
    {
        $data = [
            'message' => $formData->getMessage(),
            'createdAt' => '2020-10-24T15:01:41.072Z',
            'author' => '/api/authors/1'
        ];

        $this->client->request(
            'POST',
            'http://127.0.0.1:8000/api/comments',
            ['json' => $data]
        );
    }
}
