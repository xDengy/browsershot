<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParserImperialgorod implements Parser
{
    public function parse(string $link, string $path, string $name)
    {
        $html = file_get_contents($link);

        $crawler = new Crawler($html);

        $ids = $crawler->filter('.j-chess__building')->each(function (Crawler $node, $i) {

            return $node->attr('id');
        });

        $client = new Client(['cookies' => true]);

        $responses = [];

        foreach ($ids as $idKey => $id) {

            $responses[] = $client->post('https://www.imperialgorod.ru/api/estateChess/', [
                'form_params' => [
                    'building' => $id,
                ],
            ]);
        }

        $liter = $crawler->filter('.chess__building-text')->each(function (Crawler $node, $i) {

            return $node->text();
        });

        $newBody = [
            'complex' =>
                ['id' => md5($name),
                    'name' => $name,
                    'buildings' => [],
                ]

        ];

        foreach ($responses as $responseKey => $response) {

            $newBody ['complex']['buildings']['building'][$responseKey] = [
                'id' => '',
                'name' => '',
            ];

            $body = $response->getBody();
            $body = json_decode((string)$body, true);

            $newBody ['complex']['buildings']['building'][$responseKey]['id'] = md5($liter[$responseKey]);
            $newBody ['complex']['buildings']['building'][$responseKey]['name'] = $liter[$responseKey];

            $sections = $body['data']['sections'];
            foreach ($sections as $secKey => $section) {

                $floors = $section['floors'];

                foreach ($floors as $floorKey => $floor) {

                    $flats = $floor['flats'];

                    foreach ($flats as $flatKey => $flat) {

                        if ($flat['booked'] == 1) {

                            unset($body['data']['sections'][$secKey]['floors'][$floorKey]['flats'][$flatKey]);
                        } else {

                            if ($flat['sold'] == 1) {

                                unset($body['data']['sections'][$secKey]['floors'][$floorKey]['flats'][$flatKey]);

                                if ($body['data']['sections'][$secKey]['floors'][$floorKey]['flats'] == []) {
                                    unset($body['data']['sections'][$secKey]['floors'][$floorKey]);
                                }
                            }
                        }

                        $body['data']['sections'][$secKey]['floors'][$floorKey]['flats'] = array_values($body['data']['sections'][$secKey]['floors'][$floorKey]['flats']);
                    }
                }
            }

            foreach ($sections as $secKey => $section) {

                $floors = $section['floors'];

                foreach ($floors as $floorKey => $floor) {

                    $flats = $floor['flats'];

                    foreach ($flats as $flatKey => $flat) {

                        if ($flat['booked'] == 1 || $flat['sold'] == 1) {

                            unset($body['data']['sections'][$secKey]['floors'][$floorKey]['flats'][$flatKey]);

                        } else {

                            $href = file_get_contents('https://www.imperialgorod.ru' . $flat['href']);

                            $crawler = new Crawler($href);

                            $src = $crawler->filter('.flat__image')->each(function (Crawler $node, $i) {
                                return $node->filter('img')->each(function (Crawler $node, $i) {
                                    return $node->attr('src');
                                });
                            });

                            $newFlat = [];

                            $newFlat['apartment'] = $flat['number'];
                            $newFlat['room'] = $flat['rooms'];
                            $newFlat['price'] = str_replace('&nbsp;₽', '', $flat['price']);
                            $newFlat['area'] = str_replace('&nbsp;м²', '', $flat['area']);
                            $newFlat['floor'] = $floorKey + 1;
                            $newFlat['plan'] = $src[0][0];

                            $newBody ['complex']['buildings']['building'][$responseKey]['flats']['flat'][] =
                                $newFlat;
                        }

                    }
                }
            }
        }

        $results = ArrayToXml::convert($newBody, 'complexes');

        $dom = new DOMDocument($results);

        $dom->save($path . '.xml');

        $contents = file_get_contents($path . '.xml');

        $contents = str_replace("<?xml version='", '', $contents);
        $contents = str_replace("'?>", '', $contents);

        file_put_contents($path . '.xml', $contents);
    }
}
