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

class MoiGorodJK extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:europeya:moiGorodJK';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML europeya Moi Gorod JK';

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
        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=390',
            'Мой город ЖК', 'Мой город Литер5 Квартал3', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=391',
            'Мой город ЖК', 'Мой город Литер3 Квартал3', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=392',
            'Мой город ЖК', 'Мой город Литер7 Квартал3', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $newArr = (new \App\Services\Parsers\ParseEuropeya)->buildArray($arr, 'Мой город ЖК');

        (new \App\Services\Parsers\ParseEuropeya)->createXML($newArr, 'public/xml/europeya:moiGorodJK');
    }
}
