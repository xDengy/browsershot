<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseMagistratDon
{
    public function parse($link, $path, $name)
    {

        $crawler = new Crawler(Browsershot::url($link)
            ->bodyHtml());

        $crawler->filter('.body-big')->each(function (Crawler $node, $i) use ($path, $name)  {

            $newBody = [
                'complexes' =>
                    ['complex' =>
                        [
                            'buildings' => [],
                        ]
                    ]
            ];

            $arr = $node->filter('tr')->each(function (Crawler $node, $i) use ($newBody) {

                $text = $node->filter('td')->each(function (Crawler $node, $i) {

                    return $node->text();
                });

                $info = $node->extract(['onclick']);

                $info = explode(',', $info[0]);
                $number = str_replace('"', '', $info[4]);
                $img = str_replace('"', '', $info[9]);


                $newBody['complexes']['complex']['buildings']['building'][] = [
                    'id' => '',
                    'name' => '',
                ];

                $newBody['complexes']['complex']['buildings']['building'][0]['id'] = md5($text[0]);
                $newBody['complexes']['complex']['buildings']['building'][0]['name'] = $text[0];

                $newFlat = [];

                $newFlat['apartment'] = $number;
                $newFlat['room'] = $text[3];
                $newFlat['price'] = $text[5];
                $newFlat['area'] = $text[4];
                $newFlat['floor'] = $text[2];
                $newFlat['plan'] = $img;

                $newBody['complexes']['complex']['buildings']['building'][0]['flats']['flat'][] =
                    $newFlat;

                return $newBody;
            });

            $sortArr = [];
            $jkArr = [];

            $jkArr['complexes']['complex']['id'] = md5($name);
            $jkArr['complexes']['complex']['name'] = $name;

            foreach ($arr as $key => $item) {
                foreach ($arr as $newKey => $newItem) {
                    if ($item['complexes']['complex']['buildings']['building'][0]['name'] ==
                        $newItem['complexes']['complex']['buildings']['building'][0]['name']) {

                        $sortArr['complexes']['complex']['buildings']['building'][$newKey] = $newItem['complexes']['complex']['buildings']['building'][0];

                        unset($newItem);
                        $arr = array_values($arr);
                    }
                }
            }

            $sortArr['complexes']['complex']['buildings']['building'] = array_values($sortArr['complexes']['complex']['buildings']['building']);

            foreach ($sortArr['complexes']['complex']['buildings']['building'] as $key => $value) {
                foreach ($sortArr['complexes']['complex']['buildings']['building'] as $newKey => $item) {

                    if ($sortArr['complexes']['complex']['buildings']['building'][$key]['name']
                        == $sortArr['complexes']['complex']['buildings']['building'][$newKey]['name']) {

                        $jkArr['complexes']['complex']['buildings']['building'][$key]['name'] =
                            $sortArr['complexes']['complex']['buildings']['building'][$key]['name'];

                        $jkArr['complexes']['complex']['buildings']['building'][$key]['id'] =
                            $sortArr['complexes']['complex']['buildings']['building'][$key]['id'];

                        $jkArr['complexes']['complex']['buildings']['building'][$key]['flats']['flat'][] =
                            $sortArr['complexes']['complex']['buildings']['building'][$newKey]['flats']['flat'][0];

                    }
                }
            }

            $jkArr['complexes']['complex']['buildings']['building'] =
                array_unique($jkArr['complexes']['complex']['buildings']['building'], SORT_REGULAR);

            $jkArr['complexes']['complex']['buildings']['building'] =
                array_values($jkArr['complexes']['complex']['buildings']['building']);

            $results = ArrayToXml::convert($jkArr);

            $dom = new DOMDocument($results);

            $dom->save($path . '.xml');
        });


    }

}
