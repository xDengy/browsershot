<?php

namespace App\Console\Commands;

use App\Services\Parsers\ParseNeometria;
use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class MRUjane extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:neometria:mrUjane';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML mr Ujane';

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
        $this->info('xml:neometria:mrUjane');

        (new ParseNeometria)->parse(
            '62',
            public_path('/xml/neometria:Ujane'),
            'МР Южане');
    }
}
