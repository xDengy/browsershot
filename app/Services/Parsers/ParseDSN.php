<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseDSN implements Parser
{
    public function parse(string $link, string $path, string $complexName)
    {
        $html = file_get_contents($link);

        $crawler = new Crawler($html);

        $href = $crawler->filter('.realty_navig')->each(function (Crawler $node, $i) {
            return $node->filter('a')->last()->text();
        });

        $info = explode(' [', $href[0]);
        $info = explode(']', $info[1]);

        $jkArr['complex']['id'] = md5($complexName);
        $jkArr['complex']['name'] = $complexName;

        $jkArr['complex']['buildings']['building'][0]['id'] = md5('Тимошенко улица,5а');
        $jkArr['complex']['buildings']['building'][0]['name'] = 'Тимошенко улица,5а';

        $arr = [];

        for ($i = 1; $i <= $info[0]; $i++) {

            $html = file_get_contents('https://dsn-1.ru/services/197/?&city=0&order=1&direction=&mlspage=' . $i . '#obj');

            $crawler = new Crawler($html);

            $arr[] = $crawler->filter('div[style="padding:5px"]')->each(function (Crawler $node, $i) use ($jkArr) {

                $href = $node->filter('a')->attr('href');
                $html = file_get_contents('https://dsn-1.ru' . $href);
                $crawler = new Crawler($html);

                $apartment = explode('cn=', $href)[1];
                $apartment = explode('&', $apartment)[0];

                $floor = $crawler->filter('table[style="vertical-align:top;"]')->each(function (Crawler $node, $i) {
                    return $node->filter('td')->each(function (Crawler $node, $i) {
                        return $node->text();
                    });
                });

                $floor = explode('/', $floor[0][2])[0];

                $img = $node->filter('.img-responsive')->attr('src');

                $rooms = $node->filter('.titleObj')->text();
                $rooms = explode(' ', $rooms)[0];

                $area = $node->filter('.op1')->each(function (Crawler $node, $i) {
                    return $node->text();
                });

                $area = explode('/', $area[3])[0];

                $price = $node->filter('.price')->text();
                $price = explode(' ', $price)[0];

                return [
                    'apartment' => $apartment,
                    'room' => $rooms,
                    'price' => $price,
                    'area' => $area,
                    'floor' => $floor,
                    'plan' => 'https://dsn-1.ru' . $img,
                ];
            });

        }

        foreach ($arr as $key => $value) {
            foreach ($value as $k => $v) {

                $jkArr['complex']['buildings']['building'][0]['flats']['flat'][] = $v;

            }
        }

        $results = ArrayToXml::convert($jkArr, 'complexes');

        file_put_contents($path . '.xml', $results);
    }
}
