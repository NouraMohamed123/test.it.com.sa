<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Carbon\Carbon;

class RepeatTask extends Command
{
    protected $signature = 'task:repeat {id} {type}';
    protected $description = 'Repeat a specific record every week or every month';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $id = $this->argument('id');
        $type = $this->argument('type');

        // Fetch the record you want to duplicate
        $record = Task::find($id);

        if ($record) {
            // Create a new record with the same attributes
            $newRecord = $record->replicate();
            $newRecord->created_at = Carbon::now();
            $newRecord->updated_at = Carbon::now();

            // Update start_date and end_date based on the type of repetition
            if ($type === 'weekly') {
                $newRecord->start_date = Carbon::parse($record->start_date)->addWeek();
                $newRecord->end_date = Carbon::parse($record->end_date)->addWeek();
            } elseif ($type === 'monthly') {
                $newRecord->start_date = Carbon::parse($record->start_date)->addMonth();
                $newRecord->end_date = Carbon::parse($record->end_date)->addMonth();
            } else {
                $this->error('Invalid repetition type. Use "weekly" or "monthly".');
                return;
            }

            $newRecord->save();

            $this->info('Record repeated successfully.');
        } else {
            $this->error('Record not found.');
        }
    }
}
