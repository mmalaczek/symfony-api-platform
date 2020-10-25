<?php

namespace App\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ClientApi
{
    /**
     * @var HttpClientInterface
     */
    private $client;

    /**
     * @var string
     */
    private $apiUrl;

    /**
     * ClientApi constructor.
     * @param HttpClientInterface $client
     * @param ParameterBagInterface $params
     */
    public function __construct(HttpClientInterface $client, ParameterBagInterface $params)
    {
        $this->client = $client;
        $this->apiUrl = $params->get('api_url');
    }

    /**
     * @param int $page
     * @param string|null $nick
     * @return array
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function getComments(int $page = 1, string $nick = null): array
    {
        $url = $this->apiUrl . '/api/comments?page=' . $page;
        if ($nick) {
            $url .= '&author.nick=' . $nick;
        }

        $response = $this->client->request('GET', $url);
        return $response->toArray();
    }

    /**
     * @param $formData
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function addComment($formData): void
    {
        $data = [
            'message' => $formData->getMessage(),
            'author' => '/api/authors/1'
        ];

        $this->client->request(
            'POST',
            $this->apiUrl . '/api/comments',
            ['json' => $data]
        );
    }
}
