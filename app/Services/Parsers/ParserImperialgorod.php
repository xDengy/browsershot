<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParserImperialgorod implements Parser
{
    public function parse(string $link, string $path, string $complexName)
    {
        $html = file_get_contents($link);

        $crawler = new Crawler($html);

        $ids = $crawler->filter('.j-chess__building')->each(function (Crawler $node, $i) {

            return $node->attr('id');
        });

        $client = new Client(['cookies' => true]);

        $newBody = [
            'complex' =>
                ['id' => md5($complexName),
                 'name' => $complexName,
                 'buildings' => [],
                ]
        ];

        foreach ($ids as $idKey => $id) {
            $res = $client->post('https://www.imperialgorod.ru/api/estateChess/', [
                'form_params' => [
                    'building' => $id,
                ],
            ]);

            $liter = $crawler->filter('.chess__building-text')->each(function (Crawler $node, $i) {

                return $node->text();
            });

            $res = json_decode($res->getBody()->getContents(), true);

            $buildings = ['building' => []];
            $building['id'] = md5($liter[$idKey]);
            $building['name'] = $liter[$idKey];

            foreach ($res['data']['sections'] as $section) {
                $building = [];
                $building['flats'] = ['flat' => []];

                foreach ($section['floors'] as $floorNumber => $floor) {
                    foreach ($floor['flats'] as $apartment) {
                        if ($apartment['sold'] || $apartment['booked']) {
                            continue;
                        }

                        $building['flats']['flat'][] = [
                            'apartment' => $apartment['number'],
                            'rooms'     => $apartment['rooms'],
                            'flat'      => $floorNumber,
                            'price'     => $apartment['price'],
                        ];
                    }
                }

                $buildings['building'][] = $building;
            }
        }

        $results = ArrayToXml::convert($newBody, 'complexes');

        file_put_contents($path . '.xml', $results);
    }
}
