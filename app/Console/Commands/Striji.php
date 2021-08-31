<?php

namespace App\Console\Commands;

use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class Striji extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:imperialgorod:striji';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML imperialgorod striji';

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
        $this->info('xml:imperialgorod:striji');

        (new \App\Services\Parsers\ParserImperialgorod)->parse(
            'https://www.imperialgorod.ru/proekty/striji/',
            public_path('/xml/imperialgorod:striji'),
            'Стрижы');
    }
}
