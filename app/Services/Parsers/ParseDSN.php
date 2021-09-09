<?php

namespace App\Services\Parsers;

use App\Services\Helper;
use Symfony\Component\DomCrawler\Crawler;

class ParseDSN extends Parser
{
    public function complex(string $link, string $path, string $complexName)
    {
        $html = file_get_contents($link);

        $crawler = new Crawler($html);

        $href = $crawler->filter('.realty_navig')->each(function (Crawler $node, $i) {
            return $node->filter('a')->last()->text();
        });

        $info = explode(' [', $href[0]);
        $info = explode(']', $info[1]);

        $data = [
            'complex' => [
                'id' => md5($complexName),
                'name' => $complexName,
                'buildings' => [
                    'building' => [
                        [
                            'id' => md5('Тимошенко улица,5а'),
                            'name' => 'Тимошенко улица,5а',
                            'flats' => [
                                'flat' => []
                            ]
                        ]
                    ]
                ]
            ]
        ];

        // Pagination
        for ($i = 1; $i <= $info[0]; $i++) {
            $html = file_get_contents('https://dsn-1.ru/services/197/?&city=0&order=1&direction=&mlspage=' . $i . '#obj');
            $crawler = new Crawler($html);

            $crawler->filter('div[style="padding:5px"]')->each(function (Crawler $node, $i) use (&$data) {
                // Получение номера квартиры из ссылки
                $href = $node->filter('a')->attr('href');
                $apartment = explode('cn=', $href)[1];
                $apartment = explode('&', $apartment)[0];

                $img = $node->filter('.img-responsive')->attr('src');
                $img = explode('=', $img)[1];
                $img = explode('&', $img)[0];

                $rooms = $node->filter('.titleObj')->text();
                $rooms = Helper::clear($rooms);

                $op = $node->filter('.op1')->each(function (Crawler $node, $i) {
                    return $node->text();
                });

                $area = explode('/', $op[3])[0];

                $price = $node->filter('.price')->text();
                $price = Helper::clear($price);

                $data['complex']['buildings']['building'][0]['flats']['flat'][] = [
                    'apartment' => $apartment,
                    'room' => $rooms,
                    'price' => $price,
                    'area' => '', // Специально сделано пустая
                    'plan' => stripos($img, 'http') === false ? 'https://dsn-1.ru' . $img : $img,
                ];
            });
        }

        $this->save($data, $path);
    }
}
