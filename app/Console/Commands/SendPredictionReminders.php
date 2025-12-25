<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SendPredictionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-prediction-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send push notifications to fans who haven\'t made predictions for upcoming matches';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $upcomingMatches = \App\Models\MatchModel::where('match_date', '>', now())
            ->where('match_date', '<=', now()->addHour())
            ->where('status', 'scheduled')
            ->get();

        foreach ($upcomingMatches as $match) {
            $usersToRemind = \App\Models\User::whereDoesntHave('predictions', function ($query) use ($match) {
                $query->where('match_id', $match->id);
            })->whereHas('pushSubscriptions')->get();

            foreach ($usersToRemind as $user) {
                $this->info("Sending reminder to {$user->name} for match {$match->homeTeam->name} vs {$match->awayTeam->name}");
                
                // Logic to send push notification via WebPush library or external service would go here.
                // For now, we log the intent as requested for the "Engine" implementation.
                foreach ($user->pushSubscriptions as $subscription) {
                    // SendPushNotification::dispatch($subscription, [
                    //     'title' => 'Prediction Needed!',
                    //     'body' => "{$match->homeTeam->name} vs {$match->awayTeam->name} starts in 1 hour. Lock in your score now!",
                    //     'url' => route('predictor.index')
                    // ]);
                }
            }
        }

        return Command::SUCCESS;
    }
}
