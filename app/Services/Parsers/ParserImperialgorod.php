<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParserImperialgorod extends Parser
{
    public function complex(string $link, string $path, string $complexName)
    {
        $html = file_get_contents($link);
        $crawler = new Crawler($html);

        $ids = $crawler->filter('.j-chess__building')->each(function (Crawler $node, $i) {
            return $node->attr('id');
        });

        $client = new Client(['cookies' => true]);

        $data = [
            'complex' => [
                'id' => md5($complexName),
                'name' => $complexName,
                'buildings' => [
                    'building' => [],
                ],
            ],
        ];

        foreach ($ids as $idKey => $id) {
            $res = $client->post('https://www.imperialgorod.ru/api/estateChess/',
                [
                    'form_params' => [
                        'building' => $id,
                    ],
                ]);

            $liter = $crawler->filter('.chess__building-text')->each(function (Crawler $node, $i) {
                return $node->text();
            });

            $res = json_decode($res->getBody()->getContents(), true);

            $building = [];
            $building['id'] = md5($liter[$idKey]);
            $building['name'] = $liter[$idKey];

            foreach ($res['data']['sections'] as $section) {
                foreach ($section['floors'] as $floor) {
                    foreach ($floor['flats'] as $apartment) {
                        if ($apartment['sold'] || $apartment['booked']) {
                            continue;
                        }

                        $href = file_get_contents('https://www.imperialgorod.ru' . $apartment['href']);
                        $newCrawler = new Crawler($href);

                        $src = $newCrawler->filter('.flat__image img')->each(function (Crawler $node, $i) {
                            return $node->attr('src');
                        });

                        if ($src[0] == '') {
                            $img = $src[0];
                        } else {
                            $img = 'https://www.imperialgorod.ru' . $src[0];
                        }

                        $building['flats']['flat'][] = [
                            'apartment' => $apartment['number'],
                            'rooms' => $apartment['rooms'],
                            'area' => preg_replace('#[^0-9\.]+#', '', $apartment['area']),
                            'price' => preg_replace('#[^0-9\.]+#', '', $apartment['price']),
                            'plan' => $img ?? '',
                        ];
                    }
                }
            }

            $data['complex']['buildings']['building'][] = $building;
        }

        $this->save($data, $path);
    }
}
