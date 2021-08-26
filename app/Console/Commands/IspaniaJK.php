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

class IspaniaJK extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:europeya:ispaniaJK';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML europeya Ispania JK';

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
        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=377',
            'Испания ЖК', 'Испания Земля с домами', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=380',
            'Испания ЖК', 'Испания ИЖС (8 соток) 1 линия', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=381',
            'Испания ЖК', 'Испания ИЖС (8 соток) 2 линия', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=382',
            'Испания ЖК', 'Испания ИЖС (8 соток) основной масив', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=383',
            'Испания ЖК', 'Испания ИЖС (8 соток) аллея', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=384',
            'Испания ЖК', 'Испания ИЖС (9 соток)', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $arr[] = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=385',
            'Испания ЖК', 'Испания ИЖС (10 соток)', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $newArr = (new \App\Services\Parsers\ParseEuropeya)->buildArray($arr, 'Испания ЖК');

        (new \App\Services\Parsers\ParseEuropeya)->createXML($newArr, 'public/xml/europeya:ispaniaJK');
    }
}
