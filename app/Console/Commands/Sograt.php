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

class Sograt extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:europeya:sograt';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML europeya Sograt';

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
        $this->info('xml:europeya:sograt');

        $arr = (new \App\Services\Parsers\ParseEuropeya)->parse(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=3',
            'Сограт',
            'Сограт',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        (new \App\Services\Parsers\ParseEuropeya)->createXML($arr, public_path('/storage/xml/europeya:sograt'));
    }
}
