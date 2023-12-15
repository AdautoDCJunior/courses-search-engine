<?php

namespace Alura\CoursesSearchEngine\Tests;

use GuzzleHttp\ClientInterface;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\DomCrawler\Crawler;
use Alura\CoursesSearchEngine\SearchEngine;

class TestCoursesSearchEngine extends TestCase
{
    private $httpClientMock;
    private $url = 'url-test';

    protected function setUp(): void
    {
        $html = <<<FIM
        <html>
            <body>
                <span class="card-curso__nome">Curso Teste 1</span>
                <span class="card-curso__nome">Curso Teste 2</span>
                <span class="card-curso__nome">Curso Teste 3</span>
            </body>
        </html>
        FIM;


        $stream = $this->createMock(StreamInterface::class);
        $stream
            ->expects($this->once())
            ->method('__toString')
            ->willReturn($html);

        $response = $this->createMock(ResponseInterface::class);
        $response
            ->expects($this->once())
            ->method('getBody')
            ->willReturn($stream);

        $httpClient = $this
            ->createMock(ClientInterface::class);
        $httpClient
            ->expects($this->once())
            ->method('request')
            ->with('GET', $this->url)
            ->willReturn($response);

        $this->httpClientMock = $httpClient;
    }

    public function testCoursesSearchEngine()
    {
        $crawler = new Crawler();
        $searchEngine = new SearchEngine($this->httpClientMock, $crawler);
        $courses = $searchEngine->search($this->url);

        $this->assertCount(3, $courses);
        $this->assertEquals('Curso Teste 1', $courses[0]);
        $this->assertEquals('Curso Teste 2', $courses[1]);
        $this->assertEquals('Curso Teste 3', $courses[2]);
    }
}
