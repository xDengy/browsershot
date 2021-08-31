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

class LeventsovkaPark extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:magistrat-don:leventsovka-park';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML magistrat-don leventsovka park';

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
        $this->info('xml:magistrat-don:leventsovka-park');

        (new \App\Services\Parsers\ParseMagistratDon)->parse(
            'https://magistrat-don.ru/object/leventsovka-park/',
            public_path('/storage/xml/magistrat-don:leventsovka-park'),
            'Левенцовка парк');
    }
}
