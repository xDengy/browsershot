<?php

namespace App\Console\Commands;

use App\Services\Parsers\ParseNeometria;
use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class JKAivazovsky extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:neometria:jkAivazovsky';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML JK Aivazovsky';

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
        (new ParseNeometria)->parse(
            '24070',
            public_path('/xml/neometria:Aivazovsky'),
            'ЖК Айвазовский');
    }
}
