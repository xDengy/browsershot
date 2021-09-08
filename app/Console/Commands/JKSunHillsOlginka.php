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

class JKSunHillsOlginka extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:metriks:jkSunHillsOlginka';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML metriks JK Sun Hills Olginka';

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
        $this->info('xml:metriks:jkSunHillsOlginka');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=16',
            'ЖК Sun Hills Ольгинка',
            'Литер 1',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=18',
            'ЖК Sun Hills Ольгинка',
            'Литер 4',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $complex = (new \App\Services\Parsers\ParseEuropeya)->complex($building);

        (new \App\Services\Parsers\ParseEuropeya)->save($complex, public_path('/storage/xml/metriks:jkSunHillsOlginka'));
    }
}
