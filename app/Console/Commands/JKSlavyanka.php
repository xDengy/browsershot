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

class JKSlavyanka extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:metriks:jkSlavyanka';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML metriks JK Slavyanka';

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
        $this->info('xml:metriks:jkSlavyanka');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=19',
            'ЖК Славянка',
            'Литер 8',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=20',
            'ЖК Славянка',
            'Литер 9',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=14',
            'ЖК Славянка',
            'Литер 1',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=4',
            'ЖК Славянка',
            'Литер 2',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=6',
            'ЖК Славянка',
            'Литер 5',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=7',
            'ЖК Славянка',
            'Литер 7',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=5',
            'ЖК Славянка',
            'Литер 3',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://crm.metriks.ru/shahmatki/agent/?filter-liter=15',
            'ЖК Славянка',
            'Литер 4',
            'https://crm.metriks.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $complex = (new \App\Services\Parsers\ParseEuropeya)->complex($building);

        (new \App\Services\Parsers\ParseEuropeya)->save($complex, public_path('/storage/xml/metriks:jkSlavyanka'));
    }
}
