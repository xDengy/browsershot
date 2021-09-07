<?php

namespace App\Services\Parsers;

use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseKSM extends Parser
{
    public function parse(string $link, string $path, string $complexName)
    {
        $html = file_get_contents($link);

        $crawler = new Crawler($html);

        $data['complex']['id'] = md5($complexName);
        $data['complex']['name'] = $complexName;

        $tabs = $crawler->filter('a[role="tab"]')->each(function (Crawler $node, $i) {
            return $node->attr('aria-controls');
        });

        foreach ($tabs as $tab) {
            $tabNumber = explode('-', $tab)[1];

            $data['complex']['buildings']['building'][$tabNumber]['id'] = md5($tabNumber + 1 . '-я секция');
            $data['complex']['buildings']['building'][$tabNumber]['name'] = $tabNumber + 1 . '-я секция';

            $data['complex']['buildings']['building'][$tabNumber]['flats']['flat'] = $crawler->filter('.open-apartament')->each(function (Crawler $node, $i) use ($tabNumber) {
                if ($node->attr('data-section') == $tabNumber) {
                    $plan = $node->attr('data-plain');

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
                        'apartment' => $apartment,
                        'room' => $rooms,
                        'price' => $price,
                        'area' => $area,
                        'plan' => 'https://ksm-14st.ru/wp-block/roommap/assets/images/apartaments/plain/' . $plan,
                    ];
                }
            });

            foreach ($data['complex']['buildings']['building'][$tabNumber]['flats']['flat'] as $key => $item) {
                if ($data['complex']['buildings']['building'][$tabNumber]['flats']['flat'][$key] == null) {
                    unset($data['complex']['buildings']['building'][$tabNumber]['flats']['flat'][$key]);
                }
                if ($data['complex']['buildings']['building'][$tabNumber]['flats']['flat'] == null) {
                    unset($data['complex']['buildings']['building'][$tabNumber]);
                }
            }
        }

        $this->save($data, $path);
    }
}
