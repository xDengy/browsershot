<?php

namespace App\Console\Commands;

use App\Services\Parsers\ParseNeometria;
use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class JKUlibka extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:neometria:jkUlibka';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML JK Ulibka';

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
        $this->info('xml:neometria:jkUlibka');

        (new ParseNeometria)->complex(
            '36410',
            public_path('/storage/xml/neometria:Ulibka'),
            'ЖК Улыбка');
    }
}
