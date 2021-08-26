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


                $newBody ['complex']['buildings']['building'][] = [
                    'id' => '',
                    'name' => '',
                ];

                $newBody ['complex']['buildings']['building'][0]['id'] = md5($text[0]);
                $newBody ['complex']['buildings']['building'][0]['name'] = $text[0];

                $newFlat = [];

                $newFlat['apartment'] = $number;
                $newFlat['room'] = $text[3];
                $newFlat['price'] = $text[5];
                $newFlat['area'] = $text[4];
                $newFlat['floor'] = $text[2];
                $newFlat['plan'] = $img;

                $newBody ['complex']['buildings']['building'][0]['flats']['flat'][] =
                    $newFlat;

                return $newBody;
            });

            $sortArr = [];
            $jkArr = [];

            $jkArr ['complex']['id'] = md5($name);
            $jkArr ['complex']['name'] = $name;

            foreach ($arr as $key => $item) {
                foreach ($arr as $newKey => $newItem) {
                    if ($item ['complex']['buildings']['building'][0]['name'] ==
                        $newItem ['complex']['buildings']['building'][0]['name']) {

                        $sortArr ['complex']['buildings']['building'][$newKey] = $newItem ['complex']['buildings']['building'][0];

                        unset($newItem);
                        $arr = array_values($arr);
                    }
                }
            }

            $sortArr ['complex']['buildings']['building'] = array_values($sortArr ['complex']['buildings']['building']);

            foreach ($sortArr ['complex']['buildings']['building'] as $key => $value) {
                foreach ($sortArr ['complex']['buildings']['building'] as $newKey => $item) {

                    if ($sortArr ['complex']['buildings']['building'][$key]['name']
                        == $sortArr ['complex']['buildings']['building'][$newKey]['name']) {

                        $jkArr ['complex']['buildings']['building'][$key]['name'] =
                            $sortArr ['complex']['buildings']['building'][$key]['name'];

                        $jkArr ['complex']['buildings']['building'][$key]['id'] =
                            $sortArr ['complex']['buildings']['building'][$key]['id'];

                        $jkArr ['complex']['buildings']['building'][$key]['flats']['flat'][] =
                            $sortArr ['complex']['buildings']['building'][$newKey]['flats']['flat'][0];

                    }
                }
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
        });


    }

}
