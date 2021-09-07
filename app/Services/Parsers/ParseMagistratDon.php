<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseMagistratDon extends Parser
{
    public function parse(string $link, string $path, string $complexName)
    {
        $crawler = new Crawler(
            Browsershot::url($link)->bodyHtml()
        );

        $names = $crawler->filter('#korpus')->each(function (Crawler $node, $i) {
            return $node->filter('option')->each(function (Crawler $node, $i) {
                return $node->text();
            });
        });

        unset($names[0][0]);

        $crawler->filter('.body-big')->each(function (Crawler $node, $i) use ($path, $names, $complexName) {

            $arr = $node->filter('tr')->each(function (Crawler $node, $i) use ($names) {
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
                $newFlat['name'] = $text[0];

                if ($img == '') {
                    $newFlat['plan'] = $img;
                } else {
                    $newFlat['plan'] = 'https://magistrat-don.ru' . $img;
                }

                return $newFlat;
            });

            $data['complex']['id'] = md5($complexName);
            $data['complex']['name'] = $complexName;

            foreach ($arr as $item) {
                foreach ($names[0] as $k => $v) {
                    if ($v == $item['name']) {
                        $data['complex']['buildings']['building'][$k]['id'] = md5($v);
                        $data['complex']['buildings']['building'][$k]['name'] = $v;

                        unset($item['name']);
                        $data['complex']['buildings']['building'][$k]['flats']['flat'][] = $item;
                        break;
                    }
                }
            }

            $this->save($data, $path);
        });
    }

}
