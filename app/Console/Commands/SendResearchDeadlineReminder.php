<?php

namespace App\Console\Commands;

use App\Mail\DeadlineReminder;
use App\Models\Research;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendResearchDeadlineReminder extends Command
{

    protected $signature = 'send:research-deadline-reminder';
    protected $description = 'Send email to user about deadlines';

    public function handle()
    {
        $now = Carbon::now();
        $weekLater = Carbon::now()->addDays(7);

        $researches = Research::where(function ($query) use ($now, $weekLater) {
            $query->whereBetween('progress_report_deadline', [$now, $weekLater])->orWhereBetween('final_report_deadline', [$now, $weekLater]);
        })
            ->whereDoesntHave('progressReport')
            ->whereDoesntHave('finalReport')
            ->with('user')
            ->get();

        // $researches = $researches->filter(function ($research) {
        //     return is_null($research->progressReport) || is_null($research->finalReport);
        // });

        if ($researches->isEmpty()) {
            $this->info('No pending reminder to send.');
            return;
        }

        $researchesByUser = $researches->groupBy('user_id');
        $this->info($researches);

        foreach ($researchesByUser as $userId => $userResearch) {
            $user = $userResearch->first()->user;
            if (!$user || !$user->email) {
                $this->error("User not found for project ID: {$userResearch->first()->id}");
                continue;
            }
            try {
                Mail::to('abidcuy99@gmail.com')->send(new DeadlineReminder($user, $userResearch));
                $this->info('Reminder send successfully');
            } catch (\Exception $e) {
                $this->error("Failed to send email to {$user->email}. Error: {$e->getMessage()}");
            }
        }
        $this->info('Reminder send successfully');
    }
}
