<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseDSN extends Parser
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

        $data['complexes']['complex']['id'] = md5($complexName);
        $data['complexes']['complex']['name'] = $complexName;

        $data['complexes']['complex']['buildings']['building'][0]['id'] = md5('Тимошенко улица,5а');
        $data['complexes']['complex']['buildings']['building'][0]['name'] = 'Тимошенко улица,5а';

        $arr = [];

        for ($i = 1; $i <= $info[0]; $i++) {
            $html = file_get_contents('https://dsn-1.ru/services/197/?&city=0&order=1&direction=&mlspage=' . $i . '#obj');
            $crawler = new Crawler($html);

            $arr[] = $crawler->filter('div[style="padding:5px"]')->each(function (Crawler $node, $i) use ($data) {
                $href = $node->filter('a')->attr('href');

                $apartment = explode('cn=', $href)[1];
                $apartment = explode('&', $apartment)[0];

                $img = $node->filter('.img-responsive')->attr('src');

                $rooms = $node->filter('.titleObj')->text();
                $rooms = explode(' ', $rooms)[0];

                $op = $node->filter('.op1')->each(function (Crawler $node, $i) {
                    return $node->text();
                });

                $area = explode('/', $op[3])[0];

                $price = $node->filter('.price')->text();
                $price = explode(' ', $price)[0];

                return [
                    'apartment' => $apartment,
                    'room' => $rooms,
                    'price' => $price,
                    'area' => $area,
                    'plan' => 'https://dsn-1.ru' . $img,
                ];
            });
        }

        foreach ($arr as $value) {
            foreach ($value as $v) {
                $data['complexes']['complex']['buildings']['building'][0]['flats']['flat'][] = $v;
            }
        }

        $this->save($data, $path);
    }
}
