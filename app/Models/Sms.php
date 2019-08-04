<?php

namespace App\Models;

class Sms extends Reminder
{
    protected $connection = 'mysql';
    protected $table = 'reminder_sms';
}