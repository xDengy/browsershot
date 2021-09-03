<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Alfastroyinvest extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xml:alfastroyinvest:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create XML Alfastroyinvest';

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
        $this->info('xml:alfastroyinvest');

        exec('node ' . base_path('js/alfastroyinvest.js'));

        return 0;
    }
}
