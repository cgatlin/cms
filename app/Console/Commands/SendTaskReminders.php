<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskReminderNotification;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:send-task-reminders')]
#[Description('Command description')]
class SendTaskReminders extends Command
{
    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $tasks = Task::whereNotNull('due_date')
            ->where('is_completed', false)
            ->whereDate('due_date', '<=', now()->startOfDay()->addDay())
            ->get();

        foreach ($tasks as $task) {

            if ($task->assignedUser) {
                $task->assignedUser->notify(
                    new TaskReminderNotification($task)
                );
            }
        }

        return Command::SUCCESS;
    }
}
