<?php

namespace App\Core;

/**
 * Class Constants
 *
 * @package App\Core
 */
class Constants
{
    const ERROR_STATE               = 'error';
    const SUCCESS_STATE             = 'success';
    const DATE_FORMAT               = 'Y-m-d H:i';

    const EARLY_REMINDER_THRESHOLD_DAYS = 5;
    const TYPE_EARLY = 'early';
    const TYPE_DUE = 'due';
    const TYPE_LATE = 'late';
    const CHANNEL_EMAIL = 'email';
    const CHANNEL_SMS = 'sms';
    const CHANNEL_NOTIFICATION = 'notification';
    const NOTIFICATION_STATS_DEFAULT_PAST_DAYS = 5;
    const NOTIFICATION_STATS_DEFAULT_NEXT_DAYS = 5;
}
