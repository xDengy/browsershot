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

        foreach ($arr['complex']['buildings']['building'] as $key => $item) {

            if ($arr['complex']['buildings']['building'][$key] == null) {
                unset($arr['complex']['buildings']['building'][$key]);
            }
        }

        $arr['complex']['buildings']['building'] =
            array_values($arr['complex']['buildings']['building']);

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

        foreach ($ids as $idKey => $id) {

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

        $jkArr['complex']['id'] = md5($complexName);
        $jkArr['complex']['name'] = $complexName;
        $jkArr['complex']['buildings']['building'] = [];

        foreach ($responses as $responseKey => $response) {
            $body = $response->getBody();
            $body = json_decode((string)$body, true);

            $flat = [];

            if ($body['TYPETEXT'] == 'Квартира') {

                $flat['apartment'] = $body['NUM'];
                $flat['rooms'] = str_replace(' к.', '', $body['ROOMTEXT']);
                $flat['price'] = str_replace(' ₽', '', $body['PRICEALL']);
                $flat['area'] = $body['AREA'];
                $flat['floor'] = $body['FLOOR'];

                $img = $body['LAYOUT']['ORIGINAL_SRC'] ?? '';

                if ($img == '') {
                    $flat['plan'] = $img;
                } else {
                    $flat['plan'] = explode('/shahmatki', $link)[0] . $img;
                }

                $jkArr['complex']['buildings']['building'][$responseKey]['id'] = md5($name);
                $jkArr['complex']['buildings']['building'][$responseKey]['name'] = $name;

                $jkArr['complex']['buildings']['building'][$responseKey]['flats']['flat'][] = $flat;
            }
        }

        foreach ($jkArr['complex']['buildings']['building'] as $firstKey => $firstValue) {
            foreach ($jkArr['complex']['buildings']['building'] as $secondKey => $secondValue) {

                if ($jkArr['complex']['buildings']['building'][$firstKey]['name']
                    == $jkArr['complex']['buildings']['building'][$secondKey]['name']) {

                    $jkArr['complex']['buildings']['building'][$firstKey]['flats']['flat'][] =
                        $jkArr['complex']['buildings']['building'][$secondKey]['flats']['flat'][0];
                }
                $jkArr['complex']['buildings']['building'][$firstKey]['flats']['flat'] =
                    array_unique($jkArr['complex']['buildings']['building'][$firstKey]['flats']['flat'], SORT_REGULAR);

                $jkArr['complex']['buildings']['building'][$firstKey]['flats']['flat'] =
                    array_values($jkArr['complex']['buildings']['building'][$firstKey]['flats']['flat']);
            }
        }

        foreach ($jkArr['complex']['buildings']['building'] as $sortKey => $sortValue) {
            sort($jkArr['complex']['buildings']['building'][$sortKey]['flats']['flat']);
        }

        return $jkArr;
    }
}
