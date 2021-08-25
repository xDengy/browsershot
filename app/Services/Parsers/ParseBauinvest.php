<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseBauinvest
{

    public function parse($link, $complexName, $path)
    {
        $html = file_get_contents($link);

        $crawler = new Crawler($html);

        $jkArr['complexes']['complex']['id'] = md5($complexName);
        $jkArr['complexes']['complex']['name'] = $complexName;
        $jkArr['complexes']['complex']['buildings']['building'] = [];

        $jkArr['complexes']['complex']['buildings']['building'] =
            $crawler->filter('.spare__chess')->each(function (Crawler $node, $i) {
                return [
                    'id' => $node->filter('.spare__chessRoom-free')->each(function (Crawler $node, $i) {

                        $href = file_get_contents('https://sk-bauinvest.ru' . $node->attr('href'));

                        $crawler = new Crawler($href);

                        $name = $crawler->filter('.card__mainTitle')->each(function (Crawler $node, $i) {
                            return $node->text();
                        });

                        return md5($name[0]);
                    })[0],

                    'name' =>
                        $node->filter('.spare__chessRoom-free')->each(function (Crawler $node, $i) {

                            $href = file_get_contents('https://sk-bauinvest.ru' . $node->attr('href'));

                            $crawler = new Crawler($href);

                            $name = $crawler->filter('.card__mainTitle')->each(function (Crawler $node, $i) {
                                return $node->text();
                            });

                            return $name[0];
                        })[0],

                    'flats' => ['flat' => $node->filter('.spare__chessRoom-free')->each(function (Crawler $node, $i) {

                        $href = file_get_contents('https://sk-bauinvest.ru' . $node->attr('href'));

                        $crawler = new Crawler($href);

                        $floor = $crawler->filter('.card__li')->each(function (Crawler $node, $i) {
                            return $node->filter('.card__value')->each(function (Crawler $node, $i) {
                                return $node->text();
                            });
                        });

                        $flat = [];

                        $flat['apartment'] = $node->attr('data-num');
                        $flat['rooms'] = $node->attr('data-rooms');
                        $flat['price'] = $node->attr('data-cost-total');
                        $flat['area'] = $node->attr('data-area-full');
                        $flat['floor'] = $floor[3][0];

                        $flat['plan'] = $node->attr('data-plan-img');


                        return $flat;
                    })]
                ];
            });

        $results = ArrayToXml::convert($jkArr);

        $dom = new DOMDocument($results);

        $dom->save($path . '.xml');
    }
}
