<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseDonstroy
{
    public function createXML(array $arr, $path)
    {
        $results = ArrayToXml::convert($arr, 'complexes');

        file_put_contents($path . '.xml', $results);
    }

    public function buildAllArrays($array, $name)
    {
        $arr['complex']['id'] = md5($name);
        $arr['complex']['name'] = $name;

        foreach ($array as $key => $item) {

            $arr['complex']['buildings']['building'][] = $array[$key]['complex']['buildings']['building'][0] ??
                $array[$key]['complex']['buildings']['building'];
        }

        return $arr;
    }

    public function buildArray(array $array, $name)
    {
        $arr['complex']['buildings']['building'][0]['id'] = md5($name);
        $arr['complex']['buildings']['building'][0]['name'] = $name;

        foreach ($array as $key => $item) {

            $arr['complex']['buildings']['building'][0]['flats']['flat'][] = $array[$key]['complex']['buildings']['building'][0]['flats']['flat'];
        }

        foreach ($arr['complex']['buildings']['building'] as $key => $item) {

            $arr['complex']['buildings']['building'][0]['flats']['flat'] = array_merge(
                $arr['complex']['buildings']['building'][$key]['flats']['flat'][0],
                $arr['complex']['buildings']['building'][$key]['flats']['flat'][1]
            );
        }

        return $arr;
    }

    public function parse(string $link, string $complexName, string $sectionName)
    {
        $html = file_get_contents($link);

        $crawler = new Crawler($html);

        $jkArr['complex']['id'] = md5($complexName);
        $jkArr['complex']['name'] = $complexName;

        $jkArr['complex']['buildings']['building'][0]['id'] = md5($sectionName);
        $jkArr['complex']['buildings']['building'][0]['name'] = $sectionName;

        $jkArr['complex']['buildings']['building'][0]['flats']['flat'] =
            $crawler->filter('.span3')->each(function (Crawler $node, $i) use ($jkArr) {

                $apartment = $node->filter('a[itemprop="url"]')->text();
                $apartment = explode(' ', $apartment)[1];

                $img = 'https://donstroy.biz' . $node->filter('img[itemprop="thumbnailUrl"]')->attr('src');

                $floor = $node->filter('a[itemprop="genre"]')->text();
                $floor = explode(' ', $floor)[0];

                $rooms = $node->filter('.korpus')->text();
                $rooms = explode(' ', $rooms)[0];

                $area = $node->filter('.area')->text();
                $area = explode(' ', $area)[1];

                $price = '-';

                return [
                    'apartment' => $apartment,
                    'room' => $rooms,
                    'price' => $price,
                    'area' => $area,
                    'floor' => $floor,
                    'plan' => $img,
                ];
            });

        return $jkArr;
    }
}
