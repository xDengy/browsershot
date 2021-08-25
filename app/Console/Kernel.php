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
        RodnyeProstory::class,
        Elegant::class,
        Abrikosovo::class,
        BelyeRosy::class,
        Bosfor::class,
        FortAdmiral::class,
        SkazkaGrad::class,
        Striji::class,

        Element5::class,
        LeventsovkaPark::class,

        Sograt::class,
        ISAYPark::class,
        ZeleniyTeatrJK::class,
        PortugaliaJK::class,
        EuropeCityJK::class,
        Marriott::class,
        GermaniaJK::class,
        IspaniaJK::class,
        MoiGorodJK::class,

        JKKraski::class,
        JKSlavyanka::class,
        JKSunHillsOlginka::class,
        JKCentralniy::class,
        JKSunHills::class,

        MRUjane::class,
        JKAivazovsky::class,
        JKUlibka::class,
        JKOtrajenie::class,
        JKOblaka::class,
        JKMalina::class,
        JKPerviy::class,
        JKSkazka::class,

        JKPochtoviy::class,
        JKBauinvest::class,
        Levada::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected function schedule(Schedule $schedule)
    {
        $schedule->command('xml:imperialgorod:rodnye-prostory')->daily();
        $schedule->command('xml:imperialgorod:elegant')->daily();
        $schedule->command('xml:imperialgorod:abrikosovo')->daily();
        $schedule->command('xml:imperialgorod:belye-rosy')->daily();
        $schedule->command('xml:imperialgorod:bosfor')->daily();
        $schedule->command('xml:imperialgorod:fort-admiral')->daily();
        $schedule->command('xml:imperialgorod:skazka-grad')->daily();
        $schedule->command('xml:imperialgorod:striji')->daily();

        $schedule->command('xml:magistrat-don:5-element')->daily();
        $schedule->command('xml:magistrat-don:leventsovka-park')->daily();

        $schedule->command('xml:europeya:sograt')->daily();
        $schedule->command('xml:europeya:isayPark')->daily();
        $schedule->command('xml:europeya:zeleniyTeatrJK')->daily();
        $schedule->command('xml:europeya:portugaliaJK')->daily();
        $schedule->command('xml:europeya:europeCityJK')->daily();
        $schedule->command('xml:europeya:marriott')->daily();
        $schedule->command('xml:europeya:germaniaJK')->daily();
        $schedule->command('xml:europeya:ispaniaJK')->daily();
        $schedule->command('xml:europeya:moiGorodJK')->daily();

        $schedule->command('xml:metriks:jkKraski')->daily();
        $schedule->command('xml:metriks:jkSlavyanka')->daily();
        $schedule->command('xml:metriks:jkSunHillsOlginka')->daily();
        $schedule->command('xml:metriks:jkCentralniy')->daily();
        $schedule->command('xml:metriks:jkSunHills')->daily();

        $schedule->command('xml:neometria:mrUjane')->daily();
        $schedule->command('xml:neometria:jkAivazovsky')->daily();
        $schedule->command('xml:neometria:jkUlibka')->daily();
        $schedule->command('xml:neometria:jkOtrajenie')->daily();
        $schedule->command('xml:neometria:jkOblaka')->daily();
        $schedule->command('xml:neometria:jkMalina')->daily();
        $schedule->command('xml:neometria:jkPerviy')->daily();
        $schedule->command('xml:neometria:jkSkazka')->daily();

        $schedule->command('xml:sk-bauinvest:jkPochtoviy')->daily();
        $schedule->command('xml:sk-bauinvest:jkBauinvest')->daily();
        $schedule->command('xml:sk-bauinvest:levada')->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
