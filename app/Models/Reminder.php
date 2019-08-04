<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    /**
     * Common reminder properties
     * @var array
     */
    protected $fillable = [
        'title',
        'content',
        'invoice_id',
        'account_nr',
        'to',
        'type',
        'channel',
        'process_at',
        'is_triggered',
        'triggered_at',
        'created_at',
        'updated_at'
    ];

    /**
     * Mark as triggered and set the date to today
     *
     * @param array $ids
     */
    public function markAsTriggered(array $ids): void
    {
        static::whereIn('id', $ids)
            ->update(['is_triggered' => true, 'triggered_at' => date('Y-m-d')]);
    }

    /**
     * Gets triggered reminders based on criteria
     * @param string $accountNr
     * @param int $days
     * @return int
     */
    public function getTriggeredReminders(string $accountNr, int $days): int
    {
        $now = date('Y-m-d');
        $pastDateLimit = date('Y-m-d', strtotime("-$days days", strtotime($now)));

        $count = static::where('account_nr', $accountNr)
            ->where('is_triggered', true)
            ->whereNotNull('triggered_at')
            ->where('triggered_at', '<=', $now)
            ->where('triggered_at', '>=', $pastDateLimit)
            ->count();

        return $count;
    }

    /**
     * Gets upcoming reminders based on criteria
     * @param string $accountNr
     * @param int $days
     * @return int
     */
    public function getUpcomingReminders(string $accountNr, int $days): int
    {
        $now = date('Y-m-d');
        $futureDateLimit = date('Y-m-d', strtotime("+$days days", strtotime($now)));

        $count = static::where('account_nr', $accountNr)
            ->where('is_triggered', false)
            ->whereNull('triggered_at')
            ->where('process_at', '>=', $now)
            ->where('process_at', '<=', $futureDateLimit)
            ->count();

        return $count;
    }
}
