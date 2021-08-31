<?php

namespace App\Console\Commands;

use App\Services\Parsers\ParseBauinvest;
use App\Services\Parsers\ParseNeometria;
use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class JKPochtoviy extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:sk-bauinvest:jkPochtoviy';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML JK Pochtoviy';

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
        $this->info('xml:sk-bauinvest:jkPochtoviy');

        (new ParseBauinvest)->parse(
            'https://sk-bauinvest.ru/zhilye-kompleksy/zhk-pochtoviy',
            public_path('/xml/sk-bauinvest:jkPochtoviy'),
            'ЖК ПОЧТОВЫЙ');
    }
}
