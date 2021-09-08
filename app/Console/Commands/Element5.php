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

class Element5 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:magistrat-don:5-element';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML magistrat-don 5 element';

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
        $this->info('xml:magistrat-don:5-element');

        (new \App\Services\Parsers\ParseMagistratDon)->complex(
            'https://magistrat-don.ru/object/jk-5-element/',
            public_path('/storage/xml/magistrat-don:5-element'),
            '5 элемент');
    }
}
