<?php


namespace App\Models;


class Notification extends Reminder
{
    protected $connection = 'mysql';
    protected $table = 'reminder_notifications';
}