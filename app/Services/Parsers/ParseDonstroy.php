<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseDonstroy
{
    public function save(array $data, $path)
    {
        $results = (new ArrayToXml($data, 'complexes', true, 'UTF-8'))
            ->prettify()
            ->toXml();

        file_put_contents($path . '.xml', $results);
    }

    public function complex($buildings)
    {
        $data = array_shift($buildings);

        foreach ($buildings as $entrance) {
            $data['complex']['buildings']['building'] = array_merge(
                data_get($data, 'complex.buildings.building'),
                data_get($entrance, 'complex.buildings.building'),
            );
        }

        return $data;
    }

    public function building(array $entrances)
    {
        $data = array_shift($entrances);

        foreach ($entrances as $entrance) {
            $data['complex']['buildings']['building'][0]['flats']['flat'] = array_merge(
                data_get($data, 'complex.buildings.building.0.flats.flat'),
                data_get($entrance, 'complex.buildings.building.0.flats.flat'),
            );
        }

        return $data;
    }

    public function entrance(string $link, string $complexName, string $sectionName)
    {
        $html = file_get_contents($link);
        $crawler = new Crawler($html);

        $data = [
            'complex' => [
                'id' => md5($complexName),
                'name' => $complexName,
                'buildings' => [
                    'building' => [
                        [
                            'id' => md5($sectionName),
                            'name' => $sectionName,
                            'flats' => [
                                'flat' => $crawler->filter('.span3')->each(function (Crawler $node, $i) {
                                    $apartment = $node->filter('a[itemprop="url"]')->text();
                                    $apartment = explode(' ', $apartment)[1];

                                    $img = 'https://donstroy.biz' . $node->filter('img[itemprop="thumbnailUrl"]')->attr('src');

                                    $rooms = $node->filter('.korpus')->text();
                                    $rooms = explode(' ', $rooms)[0];

                                    $area = $node->filter('.area')->text();
                                    $area = explode(' ', $area)[1];
                                    $area = str_replace(',', '.', $area);

                                    $price = '';

                                    return [
                                        'apartment' => $apartment,
                                        'room' => $rooms,
                                        'price' => $price,
                                        'area' => $area,
                                        'plan' => $img,
                                    ];
                                }),
                            ]
                        ]
                    ]
                ]
            ],
        ];

        return $data;
    }
}
