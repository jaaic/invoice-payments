<?php


namespace App\Modules\Invoicing\Services;

use App\Core\Constants;
use App\Core\Helpers;

class ReminderGenerationService
{
    /** @var array */
    protected $invoices;

    public function __construct(array $invoices = [])
    {
        $this->invoices = $invoices;
    }

    /**
     * Populate reminders
     *
     * @return array
     */
    public function populateReminders(): array
    {
        $emailsToInsert = [];
        $smsToInsert = [];
        $notificationsToInsert = [];

        foreach ($this->invoices as $invoice) {
            $emails = $this->getRemindersToInsert($invoice, Constants::CHANNEL_EMAIL);
            $emailsToInsert = array_merge($emailsToInsert, $emails);

            $sms = $this->getRemindersToInsert($invoice, Constants::CHANNEL_SMS);
            $smsToInsert = array_merge($smsToInsert, $sms);

            $notifications = $this->getRemindersToInsert($invoice, Constants::CHANNEL_NOTIFICATION);
            $notificationsToInsert = array_merge($notificationsToInsert, $notifications);
        }

        return [
            Constants::CHANNEL_EMAIL => $emailsToInsert,
            Constants::CHANNEL_SMS => $smsToInsert,
            Constants::CHANNEL_NOTIFICATION => $notificationsToInsert
        ];
    }

    /**
     * Construct reminders according to channel
     * @param array $invoice
     * @param string $channel
     * @return array
     */
    public function getRemindersToInsert(array $invoice, string $channel): array
    {
        // assume we strictly ensure that due_date is in 'Y-m-d' format while inserting invoices
        $dueDate = $invoice['due_date'] ?? '';
        if (empty($dueDate)) {
            return [];
        }

        $now = date('Y-m-d');
        $dueDateDiff = Helpers::dateDiffInDays($now, $dueDate);

        $reminders = [];
        // due date is after early reminder threshold
        if ($dueDateDiff >= Constants::EARLY_REMINDER_THRESHOLD_DAYS) {
            $reminders[] = $this->getReminder($invoice, Constants::TYPE_EARLY, $channel);
            $reminders[] = $this->getReminder($invoice, Constants::TYPE_DUE, $channel);
            $reminders[] = $this->getReminder($invoice, Constants::TYPE_LATE, $channel);
        }

        // due date passed early reminder threshold
        if ($dueDateDiff < Constants::EARLY_REMINDER_THRESHOLD_DAYS && $dueDateDiff >= 0) {
            $reminders[] = $this->getReminder($invoice, Constants::TYPE_DUE, $channel);
            $reminders[] = $this->getReminder($invoice, Constants::TYPE_LATE, $channel);
        }

        // due date is past
        if ($dueDateDiff < 0) {
            $reminders[] = $this->getReminder($invoice, Constants::TYPE_LATE, $channel);
        }

        return $reminders;
    }

    /**
     * Get reminder content according to the type and channel
     *
     * @param array $invoice
     * @param string $type
     * @param string $channel
     * @return array
     */
    protected function getReminder(array $invoice, string $type, string $channel): array
    {
        // will be in 'Y-m-d' format if present
        $due_date = $invoice['due_date'] ?? '';

        $to = '';
        if ($channel == Constants::CHANNEL_EMAIL) {
            $to = $invoice['tenant_email'];
        } elseif ($channel == Constants::CHANNEL_SMS || Constants::CHANNEL_NOTIFICATION) {
            $to = $invoice['tenant_phone_number'];
        }

        if (empty($due_date) || empty($invoice['account_nr']) || empty($to)) {
            return [];
        }

        $content = '';
        $process_at = '';

        if ($type == Constants::TYPE_EARLY) {
            $content = 'Payment for invoice id:' . $invoice['id'] . ' for account:' . $invoice['account_nr'] . ' is due on:' . $due_date . PHP_EOL;
            $content .= 'Please ensure to pay before the due date to avoid penalty.';

            $process_at = date('Y-m-d', strtotime('-5 days', strtotime($due_date)));
        } else if ($type == Constants::TYPE_DUE) {
            $content = 'Payment for invoice id:' . $invoice['id'] . ' for account:' . $invoice['account_nr'] . ' is due today!' . PHP_EOL;
            $content .= 'Please ensure to pay today to avoid penalty.';

            $process_at = date('Y-m-d');
        } else if ($type == Constants::TYPE_LATE) {
            $content = 'Payment for invoice id:' . $invoice['id'] . ' for account:' . $invoice['account_nr'] . ' was due on:' . $due_date . PHP_EOL;
            $content .= 'Please pay asap';

            $process_at = date('Y-m-d', strtotime('+1 day', strtotime($due_date)));
        }

        if (empty($process_at)) {
            return [];
        }

        return [
            'title' => $type . ' reminder',
            'content' => $content,
            'invoice_id' => $invoice['id'],
            'account_nr' => $invoice['account_nr'],
            'to' => $to,
            'type' => $type,
            'channel' => $channel,
            'process_at' => $process_at,
            'is_triggered' => false,
            'triggered_at' => null,
            'created_at' => date('Y-m-d'),
            'updated_at' => date('Y-m-d')
        ];
    }
}
