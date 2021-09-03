<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseBauinvest implements Parser
{
    public function parse(string $link, string $path, string $complexName)
    {
        $html = file_get_contents($link);

        $crawler = new Crawler($html);

        $data['complex']['id'] = md5($complexName);
        $data['complex']['name'] = $complexName;
        $data['complex']['buildings']['building'] = [];

        $data['complex']['buildings']['building'] =
            $crawler->filter('.spare__chess')->each(function (Crawler $node, $i) use ($crawler) {
                $id = $node->attr('data-tab');

                $name = $crawler->filter('.spare__tab')->each(function (Crawler $node, $i) use ($id) {
                   if ($node->attr('data-tab') == $id) {
                       return explode(' |', $node->text())[0];
                   }
                });

                return [
                    'id' => md5($name[$i]),
                    'name' => $name[$i],
                    'flats' => ['flat' => $node->filter('.spare__chessRoom-free')->each(function (Crawler $node, $i) {
                        $flat = [];

                        $flat['apartment'] = $node->attr('data-num');
                        $flat['rooms'] = $node->attr('data-rooms');
                        $flat['price'] = str_replace(' ₽', '', $node->attr('data-cost-total'));
                        $flat['area'] = str_replace('"', '', $node->attr('data-area-full'));

                        $img = str_replace('"', '', $node->attr('data-plan-img'));
                        if ($img == '') {
                            $flat['plan'] = $img;
                        } else {
                            $flat['plan'] = 'https://sk-bauinvest.ru' . $img;
                        }

                        return $flat;
                    })]
                ];
            });

        $results = ArrayToXml::convert($data, 'complexes');

        file_put_contents($path . '.xml', $results);
    }
}
