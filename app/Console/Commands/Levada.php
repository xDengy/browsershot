<?php

namespace App\Console\Commands;

use App\Services\Parsers\ParseBauinvest;
use App\Services\Parsers\ParseNeometria;
use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class Levada extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:sk-bauinvest:levada';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML JK Levada';

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
        $this->info('xml:sk-bauinvest:levada');

        (new ParseBauinvest)->parse(
            'https://sk-bauinvest.ru/zhilye-kompleksy/levada',
            public_path('/storage/xml/sk-bauinvest:levada'),
            'ЖК Левада');
    }
}
