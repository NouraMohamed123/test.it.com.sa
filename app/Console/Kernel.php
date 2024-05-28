<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\ScheduledTask;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\RepeatTask::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */


    protected function schedule(Schedule $schedule)
    {
        $tasks = ScheduledTask::all();
        foreach ($tasks as $task) {

            if ($task->repeat_type === 'weekly') {
                $schedule->command("task:repeat {$task->task_id} {$task->repeat_type}")->weekly();
            } elseif ($task->repeat_type === 'monthly') {
                $schedule->command("task:repeat {$task->task_id} {$task->repeat_type}")->monthly();
            }
            elseif ($task->repeat_type === 'daily') {
                $schedule->command("task:repeat {$task->task_id} {$task->repeat_type}")->daily();
            }
        }
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
