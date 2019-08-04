<?php

namespace App\Models;


class Email extends Reminder
{
    protected $connection = 'mysql';
    protected $table = 'reminder_emails';
}
