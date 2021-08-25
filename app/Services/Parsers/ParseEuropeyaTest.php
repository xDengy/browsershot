<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseEuropeyaTest
{
    public function createXML($arr, $path)
    {
        $results = ArrayToXml::convert($arr);

        $dom = new DOMDocument($results);

        $dom->save($path . '.xml');
    }

    public function buildArray($array, $name)
    {
        $arr['complexes']['complex']['id'] = md5($name);
        $arr['complexes']['complex']['name'] = $name;

        foreach ($array as $key => $item) {

            $arr['complexes']['complex']['buildings']['building'][] = $array[$key]['complexes']['complex']['buildings']['building'][0];
        }

        return $arr;
    }

    public function parse()
    {
        $link = 'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=276';
        $complexName = '';
        $name = '';

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
                $responses[] = $client->post('https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?', [
                    'form_params' => [
                        'action' => 'getObjectById',
                        'id' => $id,
                    ],
                ]);
            }
        }

        $jkArr['complexes']['complex']['id'] = md5($complexName);
        $jkArr['complexes']['complex']['name'] = $complexName;
        $jkArr['complexes']['complex']['buildings']['building'] = [];

        foreach ($responses as $responseKey => $response) {
            $body = $response->getBody();
            $body = json_decode((string)$body, true);

            $flat = [];

            $flat['apartment'] = $body['NUM'];
            $flat['rooms'] = $body['ROOMTEXT'];
            $flat['price'] = $body['PRICEALL'];
            $flat['area'] = $body['AREA'];
            $flat['floor'] = $body['FLOOR'];

            $flat['plan'] = $body['LAYOUT']['ORIGINAL_SRC'] ?? '';

            $jkArr['complexes']['complex']['buildings']['building'][$responseKey]['id'] = md5($name);
            $jkArr['complexes']['complex']['buildings']['building'][$responseKey]['name'] = $name;

            $jkArr['complexes']['complex']['buildings']['building'][$responseKey]['flats']['flat'][] = $flat;
        }

        foreach ($jkArr['complexes']['complex']['buildings']['building'] as $firstKey => $firstValue) {
            foreach ($jkArr['complexes']['complex']['buildings']['building'] as $secondKey => $secondValue) {

                if ($jkArr['complexes']['complex']['buildings']['building'][$firstKey]['name']
                    == $jkArr['complexes']['complex']['buildings']['building'][$secondKey]['name']) {

                    $jkArr['complexes']['complex']['buildings']['building'][$firstKey]['flats']['flat'][] =
                        $jkArr['complexes']['complex']['buildings']['building'][$secondKey]['flats']['flat'][0];
                }
                $jkArr['complexes']['complex']['buildings']['building'][$firstKey]['flats']['flat'] =
                    array_unique($jkArr['complexes']['complex']['buildings']['building'][$firstKey]['flats']['flat'], SORT_REGULAR);

                $jkArr['complexes']['complex']['buildings']['building'][$firstKey]['flats']['flat'] =
                    array_values($jkArr['complexes']['complex']['buildings']['building'][$firstKey]['flats']['flat']);
            }
        }

        foreach ($jkArr['complexes']['complex']['buildings']['building'] as $sortKey => $sortValue) {
            sort($jkArr['complexes']['complex']['buildings']['building'][$sortKey]['flats']['flat']);
        }

        $jkArr['complexes']['complex']['buildings']['building'] =
            array_unique($jkArr['complexes']['complex']['buildings']['building'], SORT_REGULAR);

        $jkArr['complexes']['complex']['buildings']['building'] =
            array_values($jkArr['complexes']['complex']['buildings']['building']);

        echo '<pre>';
        print_r($jkArr);
    }
}
