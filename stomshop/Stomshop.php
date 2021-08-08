<?php
namespace Stomshop;
use Spider\Goutte;
use League\Csv\{Writer, Reader};

class Stomshop extends Goutte
{
    private $goutte, $csvWriter, $header = ['Наименование', 'Серийный номер/Артикул', 'Производитель', 'стоимость', 'Краткое описание'];
    public function __construct(?string $url=null)
    {
        $this->goutte =new Goutte($url);
        $this->exeption();

        $this->csvWriter = Writer::createFromPath('./list.csv', 'a+');
        $this->csvWriter->insertOne($this->header);
        $this->inner();
    }

    private function exeption()
    {
        try
        {
            if ($this->goutte == null) {
                throw new Exeption ('Стр. пуста');
            }
        }catch(Exeption $e)
        {
            echo $e->getMessage();
        }

    }

    private function getLinks()
    {
        return $this->goutte->crawler->filter('div.caption > a:link')->links();
    }

    private function openLink($item_url)
    {

        $crawler = $this->goutte->client->click($item_url);
        //$price = (!empty($crawler->filter('span.autocalc-product-special')->text()))? $crawler->filter('span.autocalc-product-special')->text() : $crawler->filter('span.autocalc-product-price')->text();
        $price = $crawler->filter('div.price-detached > span.price')->each(function ($price) {
           return $price->filter('span.price > span')->eq(0)->text();
        });
        file_put_contents(__DIR__ . '/message.txt', print_r($price, true));

        $brand = $crawler->filter('.product-points > div > h4');
        $brand = ($brand->count()>0)? $brand->text() : null;
        $description = $crawler->filter('.tab-content>.active')->text();
        $description = mb_strimwidth($description, 0, 1000, "...");
        $this->csvWriter->insertOne([
            $crawler->filter('.content-title > h1.h2, .content-title > div.h2')->text(),
            $crawler->filter('.copy-code-product')->text(),
            $brand,
            $price[0],
            $description
        ]);



    }

    private function inner()
    {
        foreach($this->getLinks() as $link){
            $this->openLink($link);
        }

    }

}
