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
        $results = (new ArrayToXml($arr, 'complexes', true, 'UTF-8'))
            ->prettify()
            ->toXml();

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

        $data['complex']['id'] = md5($complexName);
        $data['complex']['name'] = $complexName;

        $data['complex']['buildings']['building'][0]['id'] = md5($sectionName);
        $data['complex']['buildings']['building'][0]['name'] = $sectionName;

        $data['complex']['buildings']['building'][0]['flats']['flat'] =
            $crawler->filter('.span3')->each(function (Crawler $node, $i) use ($data) {
                $apartment = $node->filter('a[itemprop="url"]')->text();
                $apartment = explode(' ', $apartment)[1];

                $img = 'https://donstroy.biz' . $node->filter('img[itemprop="thumbnailUrl"]')->attr('src');

                $rooms = $node->filter('.korpus')->text();
                $rooms = explode(' ', $rooms)[0];

                $area = $node->filter('.area')->text();
                $area = explode(' ', $area)[1];
                $area = str_replace(',', '.', $area);

                $price = '-';

                return [
                    'apartment' => $apartment,
                    'room' => $rooms,
                    'price' => $price,
                    'area' => $area,
                    'plan' => $img,
                ];
            });

        return $data;
    }
}
