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

        $jkArr ['complex']['id'] = md5($complexName);
        $jkArr ['complex']['name'] = $complexName;
        $jkArr ['complex']['buildings']['building'] = [];

        $body = $response->getBody();
        $body = json_decode((string)$body, true);

        $flat = [];

        foreach ($body['apartments'] as $responseKey => $apartment) {

            $title = explode('№ ', $apartment['title']);

            $title[0] = str_replace('-комнатная кв. ', '', $title[0]);
            $title[0] = str_replace('Квартира с', 'С', $title[0]);

            $flat['apartment'] = $title[1];
            $flat['rooms'] = $title[0];
            $flat['price'] = $apartment['price'];
            $flat['area'] = $apartment['area'];
            $flat['floor'] = $apartment['floor'];
            $flat['plan'] = $apartment['image'] ?? '';

            $jkArr ['complex']['buildings']['building'][$responseKey]['id'] = md5($apartment['liter']);
            $jkArr ['complex']['buildings']['building'][$responseKey]['name'] = $apartment['liter'];

            $jkArr ['complex']['buildings']['building'][$responseKey]['flats']['flat'][] = $flat;

        }

        foreach ($jkArr ['complex']['buildings']['building'] as $firstKey => $firstValue) {
            foreach ($jkArr ['complex']['buildings']['building'] as $secondKey => $secondValue) {

                if ($jkArr ['complex']['buildings']['building'][$firstKey]['name']
                    == $jkArr ['complex']['buildings']['building'][$secondKey]['name']) {

                    $jkArr ['complex']['buildings']['building'][$firstKey]['flats']['flat'][] =
                        $jkArr ['complex']['buildings']['building'][$secondKey]['flats']['flat'][0];
                }

                $jkArr ['complex']['buildings']['building'][$firstKey]['flats']['flat'] =
                    array_unique($jkArr ['complex']['buildings']['building'][$firstKey]['flats']['flat'], SORT_REGULAR);

                $jkArr ['complex']['buildings']['building'][$firstKey]['flats']['flat'] =
                    array_values($jkArr ['complex']['buildings']['building'][$firstKey]['flats']['flat']);
            }
        }

        foreach ($jkArr ['complex']['buildings']['building'] as $sortKey => $sortValue) {
            sort($jkArr ['complex']['buildings']['building'][$sortKey]['flats']['flat']);
        }

        $jkArr ['complex']['buildings']['building'] =
            array_unique($jkArr ['complex']['buildings']['building'], SORT_REGULAR);

        $jkArr ['complex']['buildings']['building'] =
            array_values($jkArr ['complex']['buildings']['building']);


        $results = ArrayToXml::convert($jkArr, 'complexes');

        $dom = new DOMDocument($results);

        $dom->save($path . '.xml');

        $contents = file_get_contents($path . '.xml');

        $contents = str_replace("<?xml version='", '', $contents);
        $contents = str_replace("'?>", '', $contents);

        file_put_contents($path . '.xml', $contents);
    }
}
