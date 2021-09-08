<?php

namespace App\Console\Commands;

use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class Bosfor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:imperialgorod:bosfor';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML imperialgorod bosfor';

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
        $this->info('xml:imperialgorod:bosfor');

        (new \App\Services\Parsers\ParserImperialgorod)->complex(
            'https://www.imperialgorod.ru/proekty/bosfor/',
            public_path('/storage/xml/imperialgorod:bosfor'),
            'Босфор');
    }
}
