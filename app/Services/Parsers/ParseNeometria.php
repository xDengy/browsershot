<?php

namespace App\Services\Parsers;

use App\Services\Helper;
use GuzzleHttp\Client;

class ParseNeometria extends Parser
{
    public function complex(string $link, string $path, string $complexName)
    {
        $client = new Client(['cookies' => true]);

        $response = $client->get('https://neometria.ru/api/catalog/apartments?complexes[]=' . $link . '&scroll=1&page=1500');
        $body = $response->getBody();
        $body = json_decode((string) $body, true);

        $data = [
            'complex' => [
                'id' => md5($complexName),
                'name' => $complexName,
                'buildings' => [
                    'building' => collect($body['apartments'])
                        ->groupBy('liter')
                        ->map(function ($item, $liter) {
                            return [
                                'id' => md5($liter),
                                'name' => $liter,
                                'flats' => [
                                    'flat' => $item->map(function ($apartment) {
                                        [$rooms, $number] = explode('№ ', $apartment['title']);

                                        $number = str_replace('-комнатная кв. ', '', $number);
                                        $number = str_replace('Квартира с', '1', $number);

                                        return [
                                            'apartment' => Helper::clear($number),
                                            'rooms' => Helper::clear($rooms),
                                            'price' => Helper::clear($apartment['price']),
                                            'area' => Helper::clear($apartment['area']),
                                            'plan' => empty($apartment['image']) ? '' : 'https://neometria.ru' . $apartment['image'],
                                        ];
                                    })->toArray()
                                ],
                            ];
                        })
                        ->values()
                        ->toArray()
                ]
            ]
        ];

        $this->save($data, $path);
    }
}
