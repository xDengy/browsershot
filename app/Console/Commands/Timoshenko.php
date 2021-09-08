<?php

namespace App\Console\Commands;

use App\Services\Parsers\ParseDSN;
use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class Timoshenko extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:DSN:timoshenko';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML DSN timoshenko';

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
        $this->info('xml:DSN:timoshenko');

        (new ParseDSN)->complex(
            'https://dsn-1.ru/services/197/',
            public_path('/storage/xml/DSN:timoshenko'),
            'ЖК Тимошенко');
    }
}
