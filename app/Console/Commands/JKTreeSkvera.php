<?php

namespace App\Console\Commands;

use App\Services\Parsers\ParseDonstroy;
use App\Services\Parsers\ParseDSN;
use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class JKTreeSkvera extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:donstroy:jkTreeSkvera';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML donstroy jk TreeSkvera';

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
        $this->info('xml:donstroy:jkTreeSkvera');

        $arr[] = (new ParseDonstroy)->parse(
            'https://donstroy.biz/stroyashchiesya-ob-ekty/zhk-tri-skvera/31-dom/1-pod-ezd.html',
            'ЖК Три сквера', '31 дом');

        $arr[] = (new ParseDonstroy)->parse(
            'https://donstroy.biz/stroyashchiesya-ob-ekty/zhk-tri-skvera/31-dom/3-pod-ezd.html',
            'ЖК Три сквера', '31 дом');

        $arr1[] = (new ParseDonstroy)->parse(
            'https://donstroy.biz/stroyashchiesya-ob-ekty/zhk-tri-skvera/38-dom/1-pod-ezd.html',
            'ЖК Три сквера', '38 дом');

        $arr1[] = (new ParseDonstroy)->parse(
            'https://donstroy.biz/stroyashchiesya-ob-ekty/zhk-tri-skvera/38-dom/2-pod-ezd.html',
            'ЖК Три сквера', '38 дом');

        $build = (new ParseDonstroy)->buildArray(
            $arr,
            '31 дом');

        $build1 = (new ParseDonstroy)->buildArray(
            $arr1,
            '38 дом');

        $fullBuild = (new ParseDonstroy)->buildAllArrays(
            [$build, $build1],
            'ЖК Три сквера');

        (new ParseDonstroy)->createXML(
            $fullBuild,
            public_path('/storage/xml/donstroy:jkTreeSkvera'));

    }
}
