<?php

namespace App\Modules\Invoicing\Factory;

use App\Core\Constants;
use App\Models\Email;
use App\Models\Notification;
use App\Models\Reminder;
use App\Models\Sms;

class ReminderFactory
{
    /**
     * Get Reminder model by reminder type
     *
     * @param string $channel
     * @return Reminder
     */
    public function getReminderByChannel(string $channel): Reminder
    {
        if($channel == Constants::CHANNEL_EMAIL){
            return new Email();
        }
        else if($channel == Constants::CHANNEL_SMS){
            return new Sms();
        }
        else if($channel == Constants::CHANNEL_NOTIFICATION){
            return new Notification();
        } else {
            return new Reminder();
        }
    }
}