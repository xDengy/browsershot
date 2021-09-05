<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseEuropeya
{
    public function createXML(array $arr, $path)
    {
        foreach ($arr['complex']['buildings']['building'] as $key => $item) {
            if ($arr['complex']['buildings']['building'][$key] == null) {
                unset($arr['complex']['buildings']['building'][$key]);
            }
        }

        $results = ArrayToXml::convert($arr, 'complexes');
        file_put_contents($path . '.xml', $results);
    }

    public function buildArray(array $array, $name)
    {
        $arr['complex']['id'] = md5($name);
        $arr['complex']['name'] = $name;

        foreach ($array as $key => $item) {
            $arr['complex']['buildings']['building'][] = $array[$key]['complex']['buildings']['building'][0] ??
                $array[$key]['complex']['buildings']['building'];
        }

        return $arr;
    }

    public function parse($link, $complexName, $name, $post)
    {
        $html = file_get_contents($link);

        $crawler = new Crawler($html);

        $ids = $crawler->filter('.white')->each(function (Crawler $node, $i) {
            return $node->attr('data-id');
        });

        $client = new Client(['cookies' => true]);

        $responses = [];

        foreach ($ids as $id) {

            if ($id == '') {
                unset($id);
                $ids = array_values($ids);
            } else {
                $responses[] = $client->post($post, [
                    'form_params' => [
                        'action' => 'getObjectById',
                        'id' => $id,
                    ],
                ]);
            }
        }

        $data['complex']['id'] = md5($complexName);
        $data['complex']['name'] = $complexName;
        $data['complex']['buildings']['building'] = [];

        foreach ($responses as $responseKey => $response) {
            $body = $response->getBody();
            $body = json_decode((string)$body, true);

            $flat = [];

            if ($body['TYPETEXT'] == 'Квартира' || $body['TYPETEXT'] == '' || $body['TYPETEXT'] == 'Апартаменты') {

                $flat['apartment'] = $body['NUM'];
                $flat['rooms'] = explode(' ', $body['ROOMTEXT'])[0];
                $flat['price'] = str_replace(' ₽', '', $body['PRICEALL']);
                $flat['area'] = $body['AREA'];

                $img = $body['LAYOUT']['ORIGINAL_SRC'] ?? '';

                if ($img == '') {
                    $flat['plan'] = $img;
                } else {
                    $flat['plan'] = explode('/shahmatki', $link)[0] . $img;
                }

                $data['complex']['buildings']['building'][$responseKey]['id'] = md5($name);
                $data['complex']['buildings']['building'][$responseKey]['name'] = $name;

                $data['complex']['buildings']['building'][$responseKey]['flats']['flat'][] = $flat;
            }
        }

        foreach ($data['complex']['buildings']['building'] as $firstKey => $firstValue) {
            foreach ($data['complex']['buildings']['building'] as $secondKey => $secondValue) {
                $data['complex']['buildings']['building'][$firstKey]['flats']['flat'][] =
                    $data['complex']['buildings']['building'][$secondKey]['flats']['flat'][0];
            }
        }

        $data['complex']['buildings']['building'] = array_values($data['complex']['buildings']['building']);

        if (count($data['complex']['buildings']['building']) > 1) {
            $data['complex']['buildings']['building'] =
                $data['complex']['buildings']['building'][0];
        }

        return $data;
    }
}
