<?php

namespace speil\BirthdayPoster\Cron;

use XF;

class BirthdayCheck
{
    public static function runDailyCheck()
    {
        // 1. Settings
        $options = XF::options();
        
        $threadId = $options->SpeilBirthdayPosterThreadID; //in which thread the wishes should be posted  SpeilBirthdayPosterThreadID
        $userIdSender = $options->SpeilBirthdayPosterUserID; //from which user should the wishes be posted SpeilBirthdayPosterUserID
        $minPosts = $options->SpeilBirthdayPosterMinPosts;  //min number of posts the user must have, to be greeted SpeilBirthdayPosterMinPosts
        $maxInactivityDays = $options->SpeilbirthdayPosterMaxInactivity; // max days inctive

        //check
        if (!$threadId || !$userIdSender) {
            return;
        }
        
        $day = date('j');
        $month = date('n');

        // 2. User find
        $finder = XF::finder('XF:User');
        $users = $finder
            ->with('Profile')
            ->where('Profile.dob_day', $day)
            ->where('Profile.dob_month', $month)
            ->where('message_count', '>=', $minPosts)
            ->where('user_state', 'valid')
            ->where('is_banned', 0)
            // dont great yourself
            ->where('user_id', '<>', $userIdSender);
            // check inactivity
        if ($maxInactivityDays > 0) {
            $cutOffDate = XF::$time - ($maxInactivityDays * 86400); // 86400 Sec = 1 day
            $finder->where('last_activity', '>=', $cutOffDate);
        }
            
        $users = $finder->fetch();

        if ($users->count() == 0) {
            return;
        }

        // 3. collect names
        $names = [];
        foreach ($users as $user) {
            $names[] = "[USER={$user->user_id}]{$user->username}[/USER]";
        }
        $namesList = implode(', ', $names);

        // 4. write message
        $message = XF::phrase('speil_birthday_poster_msg', ['names' => $namesList]);

        // 5. Post create
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


            $replier = $app->service('XF:Thread\Replier', $thread);
            
            $replier->setMessage($message);
            $replier->setIsAutomated();
            
            if ($replier->validate()) {
                $replier->save();
            }
        });

        // 6. Log
        //XF::LogError("SpeilsBirthdayPoster: Post in thread {$threadId} created.", false);
        
    }
}
