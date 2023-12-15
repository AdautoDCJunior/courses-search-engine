<?php

namespace Alura\CoursesSearchEngine;

use GuzzleHttp\ClientInterface;
use Symfony\Component\DomCrawler\Crawler;

class SearchEngine
{
    private $httpClient;
    private $crawler;

    public function __construct(ClientInterface $httpClient, Crawler $crawler)
    {
        $this->httpClient = $httpClient;
        $this->crawler = $crawler;
    }

    public function search(string $url): array
    {
        $response = $this->httpClient->request("GET", $url);

        $html = $response->getBody();
        $this->crawler->addHtmlContent($html);

        $coursesElement = $this->crawler->filter("span.card-curso__nome");

        $courses = [];
        foreach ($coursesElement as $element) {
            $courses[] = $element->textContent;
        }

        return $courses;
    }
}
