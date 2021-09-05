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

        $data = [
            'complex' =>
                [
                    'id' => md5($complexName),
                    'name' => $complexName,
                    'buildings' =>
                        [
                            'building' => [],
                        ],
                ]
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

            $data['complex']['buildings']['building'][$idKey]['id'] = md5($liter[$idKey]);
            $data['complex']['buildings']['building'][$idKey]['name'] = $liter[$idKey];

            foreach ($res['data']['sections'] as $section) {
                foreach ($section['floors'] as $floor) {
                    foreach ($floor['flats'] as $apartment) {
                        if ($apartment['sold'] || $apartment['booked']) {
                            continue;
                        }

                        $href = file_get_contents('https://www.imperialgorod.ru' . $apartment['href']);
                        $newCrawler = new Crawler($href);

                        $src = $newCrawler->filter('.flat__image')->each(function (Crawler $node, $i) {
                            return $node->filter('img')->each(function (Crawler $node, $i) {
                                return $node->attr('src');
                            });
                        });

                        if ($src[0][0] == '') {
                            $img = $src[0][0];
                        } else {
                            $img = 'https://www.imperialgorod.ru' . $src[0][0];
                        }

                        $data['complex']['buildings']['building'][$idKey]['flats']['flat'][] = [
                            'apartment' => $apartment['number'],
                            'rooms' => $apartment['rooms'],
                            'area' => explode('&', $apartment['area'])[0],
                            'price' => explode('&', $apartment['price'])[0],
                            'plan' => $img,
                        ];
                    }
                }
            }
        }

        $results = ArrayToXml::convert($data, 'complexes');
        file_put_contents($path . '.xml', $results);
    }
}
