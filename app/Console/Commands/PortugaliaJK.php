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

class PortugaliaJK extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:europeya:portugaliaJK';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML europeya Portugalia JK';

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
        $arr = (new \App\Services\Parsers\ParseEuropeya)->parse('https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=37',
            'Португалия ЖК', 'Литер 30 Квартал3 очередь2 Португалия', 'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        (new \App\Services\Parsers\ParseEuropeya)->createXML($arr, 'public/xml/europeya:portugaliaJK');
    }
}
