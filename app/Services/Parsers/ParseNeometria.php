<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseNeometria extends Parser
{
    public function parse(string $link, string $path, string $complexName)
    {
        $client = new Client(['cookies' => true]);

        $response = $client->get('https://neometria.ru/api/catalog/apartments?complexes[]=' . $link . '&scroll=1&page=1500');

        $data['complex']['id'] = md5($complexName);
        $data['complex']['name'] = $complexName;
        $data['complex']['buildings']['building'] = [];

        $body = $response->getBody();
        $body = json_decode((string)$body, true);

        $flat = [];

        $crawler = new Crawler(
            Browsershot::url('https://neometria.ru/catalog/?complexes=[' . $link . ']')->bodyHtml()
        );

        $liters = $crawler->filter('.optionContainer')->each(function (Crawler $node, $i) {
            return $node->filter('._1qPfmP7Js2zG_IF5R0J3Un')->each(function (Crawler $node, $i) {
               return $node->text();
            });
        });

        foreach ($body['apartments'] as $apartment) {

            $title = explode('№ ', $apartment['title']);

            $title[0] = str_replace('-комнатная кв. ', '', $title[0]);
            $title[0] = str_replace('Квартира с', 'С', $title[0]);

            $flat['apartment'] = $title[1];
            $flat['rooms'] = $title[0];
            $flat['price'] = $apartment['price'];
            $flat['area'] = $apartment['area'];
            $flat['liter'] = $apartment['liter'];

            $img = $apartment['image'] ?? '';

            if ($img == '') {
                $flat['plan'] = $img;
            }
            else {
                $flat['plan'] = 'https://neometria.ru' . $img;
            }

            $flats[] = $flat;
        }

        foreach ($flats as $item) {
            foreach ($liters[1] as $k => $v) {
                if ($v == $item['liter']) {
                    $data['complex']['buildings']['building'][$k]['id'] = md5($v);
                    $data['complex']['buildings']['building'][$k]['name'] = $v;

                    unset($item['liter']);
                    $data['complex']['buildings']['building'][$k]['flats']['flat'][] = $item;

                    break;
                }
            }
        }

        $this->save($data, $path);
    }
}
