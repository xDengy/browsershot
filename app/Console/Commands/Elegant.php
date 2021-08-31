<?php

namespace App\Console\Commands;

use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class Elegant extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:imperialgorod:elegant';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML imperialgorod elegant';

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
        $this->info('xml:imperialgorod:elegant');

        (new \App\Services\Parsers\ParserImperialgorod)->parse(
            'https://www.imperialgorod.ru/proekty/elegant/',
            public_path('/xml/imperialgorod:elegant'),
            'Элегант');
    }
}
