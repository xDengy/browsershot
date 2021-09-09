<?php

namespace App\Services\Parsers;

use App\Services\Helper;
use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseKSM extends Parser
{
    public function complex(string $link, string $path, string $complexName)
    {
        $html = file_get_contents($link);

        $crawler = new Crawler($html);

        $data['complex']['id'] = md5($complexName);
        $data['complex']['name'] = $complexName;

        $tabs = $crawler->filter('a[role="tab"]')->each(function (Crawler $node, $i) {
            return $node->attr('aria-controls');
        });
        unset($tabs[2]);

        foreach ($tabs as $tab) {
            $tabNumber = explode('-', $tab)[1];

            $data['complex']['buildings']['building'][$tabNumber]['id'] = md5($tabNumber + 1 . '-я секция');
            $data['complex']['buildings']['building'][$tabNumber]['name'] = $tabNumber + 1 . '-я секция';

            $data['complex']['buildings']['building'][$tabNumber]['flats']['flat'] =
                $crawler->filter('.floor-apartaments span[data-section="' . $tabNumber . '"]')->each(function (Crawler $node, $i) use ($tabNumber) {
                $plan = $node->attr('data-plain');

                $floor = $node->attr('data-floor') + 2;

                $rooms = $node->filter('.rooms')->each(function (Crawler $node, $i) {
                    return $node->text();
                });
                $rooms = explode(' ', $rooms[1])[0];

                $area = $node->filter('.apartament-sq')->each(function (Crawler $node, $i) {
                    return $node->text();
                });
                $area = explode(' ', $area[1])[0];

                $price = $node->filter('.apartament-price')->each(function (Crawler $node, $i) {
                    return $node->text();
                });
                $price = explode(' ', $price[0])[0] . '000';

                // На сайте кривая нумерация квартир
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
                    'apartment' => $apartment,
                    'room' => $rooms,
                    'price' => Helper::clear($price),
                    'area' => Helper::clear($area),
                    'plan' => 'https://ksm-14st.ru/wp-block/roommap/assets/images/apartaments/plain/' . $plan,
                ];
            });
        }

        $this->save($data, $path);
    }
}
