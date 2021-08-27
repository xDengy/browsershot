<?php

namespace App\Console;

use App\Console\Commands\Abrikosovo;
use App\Console\Commands\BelyeRosy;
use App\Console\Commands\Bosfor;
use App\Console\Commands\Elegant;
use App\Console\Commands\EuropeCityJK;
use App\Console\Commands\FortAdmiral;
use App\Console\Commands\GermaniaJK;
use App\Console\Commands\ISAYPark;
use App\Console\Commands\IspaniaJK;
use App\Console\Commands\JKAivazovsky;
use App\Console\Commands\JKBauinvest;
use App\Console\Commands\JKCentralniy;
use App\Console\Commands\JKKraski;
use App\Console\Commands\JKMalina;
use App\Console\Commands\JKOblaka;
use App\Console\Commands\JKOtrajenie;
use App\Console\Commands\JKPerviy;
use App\Console\Commands\JKPochtoviy;
use App\Console\Commands\JKSkazka;
use App\Console\Commands\JKSlavyanka;
use App\Console\Commands\JKSunHills;
use App\Console\Commands\JKSunHillsOlginka;
use App\Console\Commands\JKUlibka;
use App\Console\Commands\Levada;
use App\Console\Commands\LeventsovkaPark;
use App\Console\Commands\Marriott;
use App\Console\Commands\MoiGorodJK;
use App\Console\Commands\MRUjane;
use App\Console\Commands\PortugaliaJK;
use App\Console\Commands\RodnyeProstory;
use App\Console\Commands\SkazkaGrad;
use App\Console\Commands\Sograt;
use App\Console\Commands\Striji;
use App\Console\Commands\Element5;
use App\Console\Commands\ZeleniyTeatrJK;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */

    protected $commands = [
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('xml:all')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */

    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
