<?php

namespace App\Services\Parsers;

use App\Services\Helper;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class ParseEuropeya
{
    public function save(array $arr, $path)
    {
        $results = (new ArrayToXml($arr, 'complexes', true, 'UTF-8'))
            ->prettify()
            ->toXml();

        file_put_contents($path . '.xml', $results);
    }

    public function complex(array $buildings)
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

    public function building($link, $complexName, $name, $post)
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

        $data = [
            'complex' => [
                'id' => md5($complexName),
                'name' => $complexName,
                'buildings' => [
                    'building' => [
                        [
                            'id' => md5($name),
                            'name' => $name,
                            'flats' => [
                                'flat' => []
                            ]
                        ]
                    ]
                ]
            ]
        ];

        foreach ($responses as $response) {
            $body = $response->getBody();
            $body = json_decode((string)$body, true);

            $flat = [];

            if ($body['TYPETEXT'] == 'Квартира' || $body['TYPETEXT'] == '' || $body['TYPETEXT'] == 'Апартаменты') {
                $flat['apartment'] = $body['NUM'];
                $flat['rooms'] = explode(' ', $body['ROOMTEXT'])[0];
                $flat['price'] = Helper::clear($body['PRICEALL']);
                $flat['area'] = $body['AREA'];

                $img = $body['LAYOUT']['ORIGINAL_SRC'] ?? '';

                if ($img == '') {
                    $flat['plan'] = $img;
                } else {
                    $flat['plan'] = explode('/shahmatki', $link)[0] . $img;
                }

                $data['complex']['buildings']['building'][0]['flats']['flat'][] = $flat;
            }
        }

        return $data;
    }
}
