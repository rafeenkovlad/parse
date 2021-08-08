<?php
namespace Spider;

use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

class Goutte
{
   protected $crawler, $client;
   public function __construct($url)
   {
       $this->client = new Client(HttpClient::create(['timeout' => 6000]));
       $this->crawler = $this->client->request('GET', $url);
   }
}
