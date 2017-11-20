<?php

namespace App\Console;

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
         \App\Console\Commands\TweetsAspers::class,
         \App\Console\Commands\TweetsEmpire::class,
         \App\Console\Commands\TweetsHippo::class,
         \App\Console\Commands\TweetsGolden::class,
         \App\Console\Commands\TweetsVic::class,
         \App\Console\Commands\TournamentAspers::class,
         \App\Console\Commands\TournamentVic::class,
         \App\Console\Commands\DailyCash::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Tweets 
        $schedule->command('tweets:aspers')->everyTenMinutes()->withoutOverlapping();;
        $schedule->command('tweets:empire')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('tweets:vic')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('tweets:hippo')->everyTenMinutes()->withoutOverlapping();

        //Tournaments
        $schedule->command('tournament:vic')->monthlyOn(1, '08:00')->withoutOverlapping();
        $schedule->command('tournament:aspers')->weekly()->mondays()->at('10:00')->withoutOverlapping();
        
        //weekly stats
        $schedule->command('daily:cash')->dailyAt('23:58')->withoutOverlapping();
    }
}
