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

class GermaniaJK extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:europeya:germaniaJK';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML europeya Germania JK';

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
        $this->info('xml:europeya:germaniaJK');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=276',
            'Германия ЖК',
            'Таунхаус Литер 1 квартал 5',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=277',
            'Германия ЖК',
            'Таунхаус Литер 2 квартал 5',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=278',
            'Германия ЖК',
            'Таунхаус Литер 3 квартал 5',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=279',
            'Германия ЖК',
            'Таунхаус Литер 4 квартал 5',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=280',
            'Германия ЖК',
            'Таунхаус Литер 5 квартал 5',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=281',
            'Германия ЖК',
            'Таунхаус Литер 6 квартал 5',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=282',
            'Германия ЖК',
            'Таунхаус Литер 7 квартал 5',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=283',
            'Германия ЖК',
            'Таунхаус Литер 8 квартал 5 ',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=284',
            'Германия ЖК',
            'Таунхаус Литер 9 квартал 5 ',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=285',
            'Германия ЖК',
            'Таунхаус Литер 10 квартал 5 ',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=286',
            'Германия ЖК',
            'Таунхаус Литер 11 квартал 5 ',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=287',
            'Германия ЖК',
            'Таунхаус Литер 12 квартал 5 ',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=288',
            'Германия ЖК',
            'Таунхаус Литер 13 квартал 5 ',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=289',
            'Германия ЖК',
            'Таунхаус Литер 14 квартал 5 ',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=290',
            'Германия ЖК',
            'Таунхаус Литер 15 квартал 5 ',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=291',
            'Германия ЖК',
            'Таунхаус Литер 16 квартал 5 ',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=366',
            'Германия ЖК',
            'Дуплекс Литер  32 (Вагнера) тип 2',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $complex = (new \App\Services\Parsers\ParseEuropeya)->complex($building);

        (new \App\Services\Parsers\ParseEuropeya)->save($complex, public_path('/storage/xml/europeya:germaniaJK'));
    }
}
