<?php

namespace App\Services\Parsers;

use App\Services\Helper;
use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class ParseBauinvest extends Parser
{
    public function complex(string $link, string $path, string $complexName)
    {
        $html = file_get_contents($link);

        $crawler = new Crawler($html);

        $data = [
            'complex' => [
                'id'        => md5($complexName),
                'name'      => $complexName,
                'buildings' => [
                    'building' => [
                        $crawler->filter('.spare__chess')->each(function (Crawler $node, $i) use ($crawler) {
                            $id = $node->attr('data-tab');

                            $name = $crawler->filter('.spare__tab[data-tab="'.$id.'"]')->each(function (Crawler $node, $i) {
                                $name = explode('|', $node->text());
                                return trim($name[0]);
                            });
                            $name = Arr::first($name);

                            return [
                                'id'    => md5($name),
                                'name'  => $name,
                                'flats' => [
                                    'flat' => $node->filter('.spare__chessRoom-free')->each(function (
                                        Crawler $node,
                                        $i
                                    ) {
                                        return [
                                            'apartment' => $node->attr('data-num'),
                                            'rooms'     => $node->attr('data-rooms'),
                                            'price'     => Helper::clear($node->attr('data-cost-total')),
                                            'area'      => Helper::clear($node->attr('data-area-full')),
                                            'plan'      => empty($node->attr('data-plan-img'))
                                                ? ''
                                                : 'https://sk-bauinvest.ru' . $node->attr('data-plan-img'),
                                        ];
                                    }),
                                ],
                            ];
                        }),
                    ],
                ],
            ],
        ];


        $this->save($data, $path);
    }
}
