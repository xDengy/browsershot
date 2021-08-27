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

        $jkArr['complex']['id'] = md5($complexName);
        $jkArr['complex']['name'] = $complexName;
        $jkArr['complex']['buildings']['building'] = [];

        $jkArr['complex']['buildings']['building'] =
            $crawler->filter('.spare__chess')->each(function (Crawler $node, $i) {
                return [
                    'id' => $node->filter('.spare__chessRoom-free')->each(function (Crawler $node, $i) {

                        $href = file_get_contents('https://sk-bauinvest.ru' . $node->attr('href'));

                        $crawler = new Crawler($href);

                        $name = $crawler->filter('.card__mainTitle')->each(function (Crawler $node, $i) {
                            return $node->text();
                        });

                        $info = explode(' | ', $name[0]);

                        return md5($info[1]);
                    })[0],

                    'name' =>
                        $node->filter('.spare__chessRoom-free')->each(function (Crawler $node, $i) {

                            $href = file_get_contents('https://sk-bauinvest.ru' . $node->attr('href'));

                            $crawler = new Crawler($href);

                            $name = $crawler->filter('.card__mainTitle')->each(function (Crawler $node, $i) {
                                return $node->text();
                            });

                            $info = explode(' | ', $name[0]);

                            return $info[1];
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
                        $flat['price'] = str_replace(' ₽', '', $node->attr('data-cost-total'));
                        $flat['area'] = str_replace('"', '', $node->attr('data-area-full'));

                        $floor = explode('/', $floor[4][0]);
                        $flat['floor'] = $floor[0];

                        $flat['plan'] = str_replace('"', '', $node->attr('data-plan-img'));

                        return $flat;
                    })]
                ];
            });

        $results = ArrayToXml::convert($jkArr, 'complexes');

        file_put_contents($path . '.xml', $results);
    }
}
