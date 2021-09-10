<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Magistrat extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xml:magistrat';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create XML magistrat';

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
        $this->info('xml:magistrat');

        exec('node ' . base_path('js/magistratdon.js'));

        return 0;
    }
}
