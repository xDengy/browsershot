<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

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
        $this->call('xml:imperialgorod:rodnye-prostory');
        $this->call('xml:imperialgorod:elegant');
        $this->call('xml:imperialgorod:abrikosovo');
        $this->call('xml:imperialgorod:belye-rosy');
        $this->call('xml:imperialgorod:bosfor');
        $this->call('xml:imperialgorod:fort-admiral');
        $this->call('xml:imperialgorod:skazka-grad');
        $this->call('xml:imperialgorod:striji');

        $this->call('xml:magistrat-don:5-element');
        $this->call('xml:magistrat-don:leventsovka-park');

        $this->call('xml:europeya:sograt');
        $this->call('xml:europeya:isayPark');
        $this->call('xml:europeya:zeleniyTeatrJK');
        $this->call('xml:europeya:portugaliaJK');
        $this->call('xml:europeya:europeCityJK');
        $this->call('xml:europeya:marriott');
        $this->call('xml:europeya:germaniaJK');
        $this->call('xml:europeya:ispaniaJK');
        $this->call('xml:europeya:moiGorodJK');

        $this->call('xml:metriks:jkKraski');
        $this->call('xml:metriks:jkSlavyanka');
        $this->call('xml:metriks:jkSunHillsOlginka');
        $this->call('xml:metriks:jkCentralniy');
        $this->call('xml:metriks:jkSunHills');

        $this->call('xml:neometria:mrUjane');
        $this->call('xml:neometria:jkAivazovsky');
        $this->call('xml:neometria:jkUlibka');
        $this->call('xml:neometria:jkOtrajenie');
        $this->call('xml:neometria:jkOblaka');
        $this->call('xml:neometria:jkMalina');
        $this->call('xml:neometria:jkPerviy');
        $this->call('xml:neometria:jkSkazka');

        $this->call('xml:sk-bauinvest:jkPochtoviy');
        $this->call('xml:sk-bauinvest:jkBauinvest');
        $this->call('xml:sk-bauinvest:levada');

        return 0;
    }
}
