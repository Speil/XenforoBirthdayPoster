<?php

namespace speil\BirthdayPoster\Cron;

use XF;

class BirthdayCheck
{
    public static function runDailyCheck()
    {
        // 1. Einstellungen
        $threadId = 1; 
        $userIdSender = 1; 
        $minPosts = 10; 
        
        $day = date('j');
        $month = date('n');

        // 2. User finden
        $finder = XF::finder('XF:User');
        $users = $finder
            ->with('Profile')
            ->where('Profile.dob_day', $day)
            ->where('Profile.dob_month', $month)
            ->where('message_count', '>=', $minPosts)
            ->where('user_state', 'valid')
            ->where('is_banned', 0)
            ->fetch();

        if ($users->count() == 0) {
            return;
        }

        // 3. Namen sammeln
        $names = [];
        foreach ($users as $user) {
            $names[] = "[USER={$user->user_id}]{$user->username}[/USER]";
        }
        $namesList = implode(', ', $names);

        // 4. Nachricht verfassen
        $message = "Heute feiert unsere aktive Community! 🎂\n\n"
                 . "Herzlichen Glückwunsch zum Geburtstag an: " . $namesList . "!\n"
                 . "Vielen Dank für eure Beiträge und alles Gute für das neue Lebensjahr! 🎉";

        // 5. Post erstellen
        $app = XF::app();
        $userSender = $app->find('XF:User', $userIdSender);
        
        if (!$userSender) {
            return;
        }

        XF::asVisitor($userSender, function() use ($threadId, $message, $app) {
            $thread = $app->find('XF:Thread', $threadId);
            if (!$thread) {
                return;
            }

            // KORREKTUR: Der Service zum Antworten heißt 'XF:Thread\Replier'
            /** @var \XF\Service\Thread\Replier $replier */
            $replier = $app->service('XF:Thread\Replier', $thread);
            
            $replier->setMessage($message);
            $replier->setIsAutomated();
            
            if ($replier->validate()) {
                $replier->save();
            }
        });
    }
}
