<?php

namespace App\Console\Commands;

use App\Services\Parsers\ParseMagistratDon;
use DOMDocument;
use Illuminate\Console\Command;
use League\CommonMark\Node\Block\Document;
use PHPHtmlParser\Dom;
use Spatie\ArrayToXml\ArrayToXml;
use Spatie\Browsershot\Browsershot;
use Symfony\Component\DomCrawler\Crawler;

class EuropeCityJK extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:europeya:europeCityJK';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML europeya Europe City JK';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {
        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=104',
            'Европа-Сити ЖК',
            'Литер 2 Квартал 5 ЕС 2 очередь',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=108',
            'Европа-Сити ЖК',
            'Литер 6 Квартал 5 ЕС 2 очередь',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=128',
            'Европа-Сити ЖК',
            'Литер 2 Квартал 6 ЕС 2 очередь',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');


        $newArr = (new \App\Services\Parsers\ParseEuropeya)->buildArray($arr, 'Европа-Сити ЖК');

        (new \App\Services\Parsers\ParseEuropeya)->createXML($newArr, public_path('/xml/europeya:europeCityJK'));
    }
}
