<?php

namespace App\Console;

use App\Models\Event;
use App\Http\Controllers\IZettleController;
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
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //update sales every minute
        $schedule->call(function () {
            $events = Event::where('activeAPI')->get();

            //get all events with active api. Practically there is only one
            foreach($events as $event) {
                //call update api function
                $this->call('App\Http\Controllers\IZettleController@getLatestSales', ['event' => $event]);
            }
        })
            ->everyMinute()
            ->withoutOverlapping()
            ->appendOutputTo(storage_path("/logs/scheduler.log"));
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
