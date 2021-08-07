<?php
namespace Stomshop;
use Spider\Goutte;
use League\Csv\{Writer, Reader};

class Stomshop extends Goutte
{
    private $goutte, $csvWriter, $header = ['Наисенование', 'Серийный номер/Артикул', 'Производитель', 'стоимость', 'Красткое описание'];
    public function __construct(?string $selector=null)
    {
        $this->goutte =new Goutte('https://stomshop.pro/stomatologicheskoye-oborudovaniye/');
        $this->csvWriter = Writer::createFromPath('./list.csv', 'w+');
        $this->inner();
    }

    private function getLinks()
    {
        return $this->goutte->crawler->filter('div.caption > a:link')->links();
    }

    private function openLink($item_url)
    {
        $crawler = $this->goutte->client->click($item_url);
        $this->csvWriter->insertOne([
            $crawler->filter('.content-title > h1.h2, .content-title > div.h2')->text(),
            $crawler->filter('.copy-code-product')->text(),
            $crawler->filter('.product-points > div > h4')->text(),
            $crawler->filter('span.autocalc-product-special')->text(),

        ]);



    }

    private function inner()
    {
        foreach($this->getLinks() as $link){
            $this->openLink($link);
        }

    }

}
