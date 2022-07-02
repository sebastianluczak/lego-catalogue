<?php

namespace App\Services;

use App\Models\LegoSetView;
use Illuminate\Support\Facades\Http;
use Goutte\Client;
use Illuminate\Support\Facades\Log;
use Symfony\Component\DomCrawler\Crawler;

class RebrickableService
{
    // ugly as hell, service that does everything. I don't care
    public function getBySetNumber(int $setNumber)
    {
        $response = Http::withHeaders([
            'Authorization' => "key 284bf47cfa65ebd66c57e0a5b57a4e2d"
        ])->get('https://rebrickable.com/api/v3/lego/sets/'.$setNumber.'-1/');

        $content = json_decode($response->body());

        return new LegoSetView($content);
    }

    public function getThemeNameForThemeId(int $themeId)
    {
        $response = Http::withHeaders([
            'Authorization' => "key 284bf47cfa65ebd66c57e0a5b57a4e2d"
        ])->get('https://rebrickable.com/api/v3/lego/themes/'.$themeId.'/');
        $content = json_decode($response->body());

        return $content->name;
    }

    public function getPromoklockiPriceForSet(LegoSetView $legoSetView)
    {
        $client = new Client();
        $uri = 'https://promoklocki.pl/' . $legoSetView->getSetNumber();
        Log::notice("getting " . $uri);

        $crawler = $client->request('GET', $uri);
        $prices = [];
        // Click on the "Security Advisories" link
        $crawler->filter('.bprice')
            ->each(function (Crawler $node) use ($legoSetView, &$prices) {
                Log::notice("Found " . $node->nodeName() . ' with text: ' . $node->text());
                $prices[] = $node->text();
            });

        if (count($prices) > 0) {
            foreach ($prices as $price) {
                if (strpos($price, 'zł') !== false) {
                    $legoSetView->setPromoklockiPriceForSet($price);
                    break;
                }
            }
        }
    }
    public function getCeneoPriceForSet(LegoSetView $legoSetView)
    {
        $client = new Client();
        $uri = 'https://www.ceneo.pl/;szukaj-lego+' . $legoSetView->getSetNumber();
        Log::notice("getting " . $uri);

        $crawler = $client->request('GET', $uri);

        // Click on the "Security Advisories" link
        $link = $crawler->selectLink('Porównaj ceny')->link();
        Log::notice("Link found: " . $link->getUri());
        $crawler = $client->click($link);
        // Get the latest post in this category and display the titles
        $classPath = '.product-offer-summary > .product-offer-summary__price-box--with-icon';
        Log::notice("[FIRST METHOD] Looking for " . $classPath);
        $crawler->filter('.product-offer-summary > .product-offer-summary__price-box--with-icon')
            ->each(function (Crawler $node) use ($legoSetView) {
                Log::notice("Found " . $node->nodeName() . ' with text: ' . $node->text());
                $legoSetView->setCeneoPrice($node->text());
        });
        // not found price? Maybe abother method
        // warto dodaćIF TODO
        Log::notice("[SECOND METHOD] Looking for " . $classPath);
        $crawler->filter('.product-offer-summary > .product-offer-summary__price-box')
            ->each(function (Crawler $node) use ($legoSetView) {
                Log::notice("Found " . $node->nodeName() . ' with text: ' . $node->text());
                $legoSetView->setCeneoPrice($node->text());
            });

    }

    public function getBrickLinkPriceForSet(LegoSetView $legoSetView)
    {
        $uri = "https://www.bricklink.com/catalogPG.asp?S=".$legoSetView->getSetNumber()."-1-&colorID=0&v=P&viewExclude=Y&cID=Y";
        $client = new Client();
        Log::notice("getting " . $uri);

        $crawler = $client->request('GET', $uri);

        $crawler->filterXPath('//*[@id="id-main-legacy-table"]')
            ->each(function (Crawler $node) use ($legoSetView) {
                if (str_contains($node->text(), 'Avg Price:')) {
                    dump($node->text());
                    $re = '/\d{1,3}(?:[.,]\d{3})*(?:[.,]\d{2})/m';
                    preg_match_all($re, $node->text(), $matches);
                    Log::notice("Found " . $node->nodeName() . ' with text: ' . $node->text());
                    array_pop($matches[0]);
                    $actualPrice = last($matches[0]);
                    $legoSetView->setPromoklockiPriceForSet($actualPrice. " zł");
                }
            });
    }
}
