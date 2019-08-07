<?php

namespace App\Console\Commands;

use App\Core\Constants;
use App\Models\Reminder;
use App\Modules\Invoicing\Factory\ReminderFactory;
use App\Notifications\PaymentReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TriggerPaymentReminders extends Command
{
    /** @var string */
    protected $signature = 'trigger-paymentReminders';

    /** @var string */
    protected $description = 'trigger payment reminders such as emails, sms, notifications';

    public function execute(InputInterface $input, OutputInterface $output)
    {
        // get the reminders for unpaid invoices with process_at date = today
        $unpaidEmails = $this->getUnpaidReminders(Constants::CHANNEL_EMAIL);
        $unpaidSms = $this->getUnpaidReminders(Constants::CHANNEL_SMS);
        $unpaidNotifications = $this->getUnpaidReminders(Constants::CHANNEL_NOTIFICATION);

        // trigger the reminders via mail, sms, broadcast
        $this->triggerReminders($unpaidEmails, Constants::CHANNEL_EMAIL);
        $this->triggerReminders($unpaidSms, Constants::CHANNEL_SMS);
        $this->triggerReminders($unpaidNotifications, Constants::CHANNEL_NOTIFICATION);
    }

    /**
     * Gets reminders to be processed today with unpaid invoices
     *
     * @param string $channel
     * @return array
     */
    protected function getUnpaidReminders(string $channel): array
    {
        $reminderFactory = new ReminderFactory();
        $reminderModel = $reminderFactory->getReminderByChannel($channel);
        $reminderTable = $reminderModel->getTable();

        $reminders = DB::table($reminderTable)
            ->join('invoices', "$reminderTable.invoice_id", '=', 'invoices.id')
            ->select("$reminderTable.id", "$reminderTable.title", "$reminderTable.content", "$reminderTable.account_nr", "$reminderTable.to", "$reminderTable.invoice_id")
            ->where("$reminderTable.process_at", '=', date('Y-m-d'))
            ->where('invoices.isPaid', '=', false)
            ->get();

        return (!empty($reminders)) ? $reminders->toArray() : [];
    }

    /**
     * Send reminder via mail, nexmo for sms and broadcast for notifications
     *
     * @param array $reminders
     * @param string $channel
     */
    protected function triggerReminders(array $reminders, string $channel): void
    {
        $route = '';
        if ($channel == Constants::CHANNEL_EMAIL) {
            $route = 'mail';
        } else if ($channel == Constants::CHANNEL_SMS) {
            $route = 'nexmo';
        } else if ($channel == Constants::CHANNEL_NOTIFICATION) {
            $route = 'broadcast';
        }

        $triggeredReminders = [];
        // route notification to respective channel : mail, sms or broadcast
        foreach ($reminders as $reminder) {
            if (!empty($reminder->to && !empty($route))) {
                Notification::route($route, $reminder->to)
                    ->notify(new PaymentReminder($reminder));
            }
            // collect the reminder id to be marked in order to not trigger next time
            $triggeredReminders[] = $reminder->id;
        }

        // mark the triggered reminders as sent
        $this->markSentReminders($triggeredReminders, $channel);
    }

    /**
     * Mark triggered reminders
     *
     * @param array $reminderIds
     * @param string $channel
     */
    protected function markSentReminders(array $reminderIds, string $channel): void
    {
        $reminderFactory = new ReminderFactory();
        /** @var Reminder $reminderModel */
        $reminderModel = $reminderFactory->getReminderByChannel($channel);
        $reminderModel->markAsTriggered($reminderIds);
    }
}
