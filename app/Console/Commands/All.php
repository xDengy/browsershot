<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class All extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'xml:all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Все ЖК';

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
        $this->tryToCall('xml:imperialgorod:rodnye-prostory');
        $this->tryToCall('xml:imperialgorod:elegant');
        $this->tryToCall('xml:imperialgorod:abrikosovo');
        $this->tryToCall('xml:imperialgorod:belye-rosy');
        $this->tryToCall('xml:imperialgorod:bosfor');
        $this->tryToCall('xml:imperialgorod:fort-admiral');
        $this->tryToCall('xml:imperialgorod:skazka-grad');
        $this->tryToCall('xml:imperialgorod:striji');

        $this->tryToCall('xml:magistrat-don:5-element');
        $this->tryToCall('xml:magistrat-don:leventsovka-park');

        $this->tryToCall('xml:europeya:sograt');
        $this->tryToCall('xml:europeya:isayPark');
        $this->tryToCall('xml:europeya:zeleniyTeatrJK');
        $this->tryToCall('xml:europeya:portugaliaJK');
        $this->tryToCall('xml:europeya:europeCityJK');
        $this->tryToCall('xml:europeya:marriott');
        $this->tryToCall('xml:europeya:germaniaJK');
        $this->tryToCall('xml:europeya:ispaniaJK');
        $this->tryToCall('xml:europeya:moiGorodJK');

        $this->tryToCall('xml:metriks:jkKraski');
        $this->tryToCall('xml:metriks:jkSlavyanka');
        $this->tryToCall('xml:metriks:jkSunHillsOlginka');
        $this->tryToCall('xml:metriks:jkCentralniy');
        $this->tryToCall('xml:metriks:jkSunHills');

        $this->tryToCall('xml:neometria:mrUjane');
        $this->tryToCall('xml:neometria:jkAivazovsky');
        $this->tryToCall('xml:neometria:jkUlibka');
        $this->tryToCall('xml:neometria:jkOtrajenie');
        $this->tryToCall('xml:neometria:jkOblaka');
        $this->tryToCall('xml:neometria:jkMalina');
        $this->tryToCall('xml:neometria:jkPerviy');
        $this->tryToCall('xml:neometria:jkSkazka');

        $this->tryToCall('xml:sk-bauinvest:jkPochtoviy');
        $this->tryToCall('xml:sk-bauinvest:jkBauinvest');
        $this->tryToCall('xml:sk-bauinvest:levada');


        $this->tryToCall('xml:DSN:timoshenko');

        $this->tryToCall('xml:KSM:RIIJTskiyUyut');

        $this->tryToCall('xml:donstroy:jkZvezdniy2');
        $this->tryToCall('xml:donstroy:jkTreeSkvera');

        return 0;
    }

    public function tryToCall(string $command)
    {
        try {
            $this->call($command);
        } catch (\Throwable $e) {
            $this->error($command);
            $this->error($e->getMessage());
            Log::info($e->getMessage(), $e->getTrace());
        }
    }
}
