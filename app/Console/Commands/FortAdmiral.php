<?php

namespace App\Console\Commands;

use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class FortAdmiral extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:imperialgorod:fort-admiral';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML imperialgorod fort-admiral';

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
        $this->info('xml:imperialgorod:fort-admiral');

        (new \App\Services\Parsers\ParserImperialgorod)->parse(
            'https://www.imperialgorod.ru/proekty/fort-admiral/',
            public_path('/xml/imperialgorod:fort-admiral'),
            'Форт адмирал'
        );
    }
}
