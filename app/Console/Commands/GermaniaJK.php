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
        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=276',
            'Германия ЖК', 'Таунхаус Литер1 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=277',
            'Германия ЖК', 'Таунхаус Литер2 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=278',
            'Германия ЖК', 'Таунхаус Литер3 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=279',
            'Германия ЖК', 'Таунхаус Литер4 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=280',
            'Германия ЖК', 'Таунхаус Литер5 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=281',
            'Германия ЖК', 'Таунхаус Литер6 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=282',
            'Германия ЖК', 'Таунхаус Литер7 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=283',
            'Германия ЖК', 'Таунхаус Литер8 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=284',
            'Германия ЖК', 'Таунхаус Литер9 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=285',
            'Германия ЖК', 'Таунхаус Литер10 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=286',
            'Германия ЖК', 'Таунхаус Литер11 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=287',
            'Германия ЖК', 'Таунхаус Литер12 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=288',
            'Германия ЖК', 'Таунхаус Литер13 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=289',
            'Германия ЖК', 'Таунхаус Литер14 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=290',
            'Германия ЖК', 'Таунхаус Литер15 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=291',
            'Германия ЖК', 'Таунхаус Литер16 квартал5 Германия ЖК', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=366',
            'Германия ЖК', 'Дуплекс Литер 32 (Вагнера) тип2', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $newArr = (new \App\Services\Parsers\ParseEuropeya)->buildArray($arr, 'Германия ЖК');

        (new \App\Services\Parsers\ParseEuropeya)->createXML($newArr, 'public/xml/europeya:germaniaJK');
    }
}
