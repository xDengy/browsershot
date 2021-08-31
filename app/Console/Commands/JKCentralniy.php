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

class JKCentralniy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:metriks:jkCentralniy';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML metriks JK Centralniy';

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
        $this->info('xml:metriks:jkCentralniy');

        $arr = (new \App\Services\Parsers\ParseEuropeya)->parse(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=10',
            'ЖК Центральный',
            'Литер 1',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        (new \App\Services\Parsers\ParseEuropeya)->createXML($arr, public_path('/xml/metriks:jkCentralniy'));
    }
}
