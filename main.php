<?php

require __DIR__ . "/vendor/autoload.php";

use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;
use Alura\CoursesSearchEngine\SearchEngine;

$client = new Client(["base_uri" => "https://www.alura.com.br/"]);
$crawler = new Crawler();

$searchEngine = new SearchEngine($client, $crawler);

$courses = $searchEngine->search("/cursos-online-programacao/php");

foreach ($courses as $course) {
    echo $course . PHP_EOL;
}