<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseMagistratDon extends Parser
{
    public function complex(string $link, string $path, string $complexName)
    {
        $crawler = new Crawler(
            Browsershot::url($link)->bodyHtml()
        );

        $names = $crawler->filter('#korpus option')->each(function (Crawler $node, $i) {
            return $node->text();
        });

        unset($names[0]);

        $data = [
            'complexes' => [
                'complex' => [
                    'id' => md5($complexName),
                    'name' => $complexName,
                    'buildings' => [
                        'building' => [
                            [

                            ]
                        ]
                    ]
                ]
            ]
        ];

        $crawler->filter('.body-big')->each(function (Crawler $node, $i) use ($path, $names, $complexName, $data) {
            foreach ($names as $nameKey => $name) {
                $node->filter('tr')->each(function (Crawler $node, $i) use ($name, $nameKey, $data) {
                    $text = $node->filter('td')->each(function (Crawler $node, $i) {
                        return $node->text();
                    });

                    $info = $node->extract(['onclick']);

                    $info = explode(',', $info[0]);
                    $number = str_replace('"', '', $info[4]);
                    $img = str_replace('"', '', $info[9]);

                    $newFlat = [];

                    $newFlat['apartment'] = $number;
                    $newFlat['room'] = $text[3];
                    $newFlat['price'] = $text[5];
                    $newFlat['area'] = $text[4];

                    if ($img == '') {
                        $newFlat['plan'] = $img;
                    } else {
                        $newFlat['plan'] = 'https://magistrat-don.ru' . $img;
                    }

                    if ($name == $text[0]) {
                        $data['complex']['buildings']['building'][$nameKey]['id'] = md5($name);
                        $data['complex']['buildings']['building'][$nameKey]['name'] = $name;
                        $data['complex']['buildings']['building'][$nameKey]['flats']['flat'][] = $newFlat;
                    }
                });
            }

            $this->save($data, $path);
        });
    }

}
