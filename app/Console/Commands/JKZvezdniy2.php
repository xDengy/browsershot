<?php

namespace App\Console\Commands;

use App\Services\Parsers\ParseDonstroy;
use App\Services\Parsers\ParseDSN;
use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class JKZvezdniy2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:donstroy:jkZvezdniy2';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML donstroy jk Zvezdniy-2';

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
        $this->info('xml:donstroy:jkZvezdniy2');

        $buildings = [];

        $entrances = [];
        $entrances[] = (new ParseDonstroy)->entrance(
            'https://donstroy.biz/stroyashchiesya-ob-ekty/zhk-zvezdnyj-2/4-etap/sektsiya-1.html',
            'ЖК Звездный-2', '4 этап');
        $entrances[] = (new ParseDonstroy)->entrance(
            'https://donstroy.biz/stroyashchiesya-ob-ekty/zhk-zvezdnyj-2/3-etap/sektsiya-1.html',
            'ЖК Звездный-2', '4 этап');
        $buildings[] = (new ParseDonstroy)->building($entrances);

        $entrances = [];
        $entrances[] = (new ParseDonstroy)->entrance(
            'https://donstroy.biz/stroyashchiesya-ob-ekty/zhk-zvezdnyj-2/3-etap/sektsiya-1.html',
            'ЖК Звездный-2', '3 этап');
        $entrances[] = (new ParseDonstroy)->entrance(
            'https://donstroy.biz/stroyashchiesya-ob-ekty/zhk-zvezdnyj-2/3-etap/sektsiya-2.html',
            'ЖК Звездный-2', '3 этап');
        $buildings[] = (new ParseDonstroy)->building($entrances);

        $complex = (new ParseDonstroy)->complex($buildings);

        (new ParseDonstroy)->save(
            $complex,
            public_path('/storage/xml/donstroy:jkZvezdniy2')
        );
    }
}
