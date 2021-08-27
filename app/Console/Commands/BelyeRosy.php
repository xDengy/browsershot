<?php

namespace App\Console\Commands;

use App\Console\ParserImperialgorod;
use Illuminate\Console\Command;
use DOMDocument;
use GuzzleHttp\Client;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class BelyeRosy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:imperialgorod:belye-rosy';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML imperialgorod belye-rosy';

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
        (new \App\Services\Parsers\ParserImperialgorod)->parse(
            'https://www.imperialgorod.ru/proekty/belye-rosy/',
            public_path('/xml/imperialgorod:belye-rosy'),
            'Белые розы');
    }
}
