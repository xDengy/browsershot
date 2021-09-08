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
        $this->info('xml:europeya:moiGorodJK');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=390',
            'Мой город ЖК',
            'Литер 5 Квартал 3',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=391',
            'Мой город ЖК',
            'Литер 3 Квартал 3',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $building[] = (new \App\Services\Parsers\ParseEuropeya)->building(
            'https://bitrix.europeya.ru/shahmatki/agent/?filter-liter=392',
            'Мой город ЖК',
            'Литер 7 Квартал 3',
            'https://bitrix.europeya.ru/local/components/itiso/shahmatki.lists/ajax.php?');

        $complex = (new \App\Services\Parsers\ParseEuropeya)->complex($building, 'Мой город ЖК');

        (new \App\Services\Parsers\ParseEuropeya)->save($complex, public_path('/storage/xml/europeya:moiGorodJK'));
    }
}
