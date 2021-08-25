<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseNeometria
{

    public function parse($complexID, $complexName, $path)
    {
        $client = new Client(['cookies' => true]);

        $response = $client->get('https://neometria.ru/api/catalog/apartments?complexes[]=' . $complexID . '&scroll=1&page=1500');

        $jkArr['complexes']['complex']['id'] = md5($complexName);
        $jkArr['complexes']['complex']['name'] = $complexName;
        $jkArr['complexes']['complex']['buildings']['building'] = [];

        $body = $response->getBody();
        $body = json_decode((string)$body, true);

        $flat = [];

        foreach ($body['apartments'] as $responseKey => $apartment) {

            $title = explode('№', $apartment['title']);

            $flat['apartment'] = $title[1];
            $flat['rooms'] = $title[0];
            $flat['price'] = $apartment['price'];
            $flat['area'] = $apartment['area'];
            $flat['floor'] = $apartment['floor'];

            $flat['plan'] = $apartment['image'] ?? '';

            $jkArr['complexes']['complex']['buildings']['building'][$responseKey]['id'] = md5($apartment['liter']);
            $jkArr['complexes']['complex']['buildings']['building'][$responseKey]['name'] = $apartment['liter'];

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


        $results = ArrayToXml::convert($jkArr);

        $dom = new DOMDocument($results);

        $dom->save($path . '.xml');
    }
}
