<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseKSM implements Parser
{
    public function parse(string $link, string $path, string $complexName)
    {
        $html = file_get_contents($link);

        $crawler = new Crawler($html);

        $jkArr['complex']['id'] = md5($complexName);
        $jkArr['complex']['name'] = $complexName;

        $jkArr['complex']['buildings']['building'] = $crawler->filter('.open-apartament')->each(function (Crawler $node, $i) {

            $plan = $node->attr('data-plain');

            $name = $node->attr('data-section') + 1 . '-я секция';

            $floor = $node->attr('data-floor') + 2;

            $rooms = explode(' ', $node->filter('.rooms')->each(function (Crawler $node, $i) {
                return $node->text();
            })[1]
            )[0];

            $area = explode(' ', $node->filter('.apartament-sq')->each(function (Crawler $node, $i) {
                return $node->text();
            })[1]
            )[0];

            $price = explode(' ',
                    $node->filter('.apartament-price')->each(function (Crawler $node, $i) {
                        return $node->text();
                    })[0]
                )[0] . '000';

            if ($node->attr('data-section') == 3) {

                if ($rooms == 1) {

                    $apartment = $floor - 1;
                } else {
                    $apartment = explode('-', $plan)[1];
                    if ($floor > 7) {
                        $apartment = explode('.', $apartment)[0] - 6;
                    } else {
                        $apartment = explode('.', $apartment)[0] - ($floor - 2);
                    }
                }
            } else {
                $apartment = explode('_', $plan)[1];
                $apartment = explode('n', $apartment)[0];
            }

            return [
                'id' => md5($name),
                'name' => $name,
                'flats' =>
                    ['flat' => [
                        [
                            'apartment' => $apartment,
                            'room' => $rooms,
                            'price' => $price,
                            'area' => $area,
                            'floor' => $floor,
                            'plan' => 'https://ksm-14st.ru/wp-block/roommap/assets/images/apartaments/plain/' . $plan,
                        ]
                    ]
                    ]
            ];
        });

        foreach ($jkArr['complex']['buildings']['building'] as $firstKey => $firstValue) {
            foreach ($jkArr['complex']['buildings']['building'] as $secondKey => $secondValue) {

                if ($jkArr['complex']['buildings']['building'][$firstKey]['name']
                    == $jkArr['complex']['buildings']['building'][$secondKey]['name']) {

                    $jkArr['complex']['buildings']['building'][$firstKey]['flats']['flat'][] =
                        $jkArr['complex']['buildings']['building'][$secondKey]['flats']['flat'][0];
                }
                $jkArr['complex']['buildings']['building'][$firstKey]['flats']['flat'] =
                    array_unique($jkArr['complex']['buildings']['building'][$firstKey]['flats']['flat'], SORT_REGULAR);

                $jkArr['complex']['buildings']['building'][$firstKey]['flats']['flat'] =
                    array_values($jkArr['complex']['buildings']['building'][$firstKey]['flats']['flat']);
            }
        }

        foreach ($jkArr['complex']['buildings']['building'] as $sortKey => $sortValue) {
            sort($jkArr['complex']['buildings']['building'][$sortKey]['flats']['flat']);
        }

        $jkArr['complex']['buildings']['building'] =
            array_unique($jkArr['complex']['buildings']['building'], SORT_REGULAR);

        $jkArr['complex']['buildings']['building'] =
            array_values($jkArr['complex']['buildings']['building']);

        $results = ArrayToXml::convert($jkArr, 'complexes');

        file_put_contents($path . '.xml', $results);

    }
}
