<?php

namespace App\Console\Commands;

use App\Services\Parsers\ParseDSN;
use App\Services\Parsers\ParseKSM;
use DOMDocument;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use Spatie\ArrayToXml\ArrayToXml;
use Symfony\Component\DomCrawler\Crawler;

class RIIJTskiyUyut extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'xml:KSM:RIIJTskiyUyut';

    /**
     * The console command description.
     *
     * @var string
     */

    protected $description = 'Create XML KSM RIIJTskiy Uyut';

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
        $this->info('xml:KSM:RIIJTskiyUyut');

        (new ParseKSM)->complex(
            'https://ksm-14st.ru/choose-a-flat/',
            public_path('/storage/xml/KSM:RIIJTskiyUyut'),
            'ЖК РИИЖТский УЮТ');
    }
}
