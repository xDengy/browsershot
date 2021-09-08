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

class JKKraski extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:metriks:jkKraski';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML metriks JK Kraski';

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
        $this->info('xml:metriks:jkKraski');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=21',
            'ЖК Краски',
            'Литер 5',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=13',
            'ЖК Краски',
            'Литер 2',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=17',
            'ЖК Краски',
            'Литер 8',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=3',
            'ЖК Краски',
            'Литер 4',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=11',
            'ЖК Краски',
            'Литер 7',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=2',
            'ЖК Краски',
            'Литер 3',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=1',
            'ЖК Краски',
            'Литер 1',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $complex = (new \App\Services\Parsers\ParseEuropeya)->complex($building);

        (new \App\Services\Parsers\ParseEuropeya)->save($complex, public_path('/storage/xml/metriks:jkKraski'));
    }
}
