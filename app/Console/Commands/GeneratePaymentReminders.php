<?php

namespace App\Console\Commands;

use App\Core\Constants;
use App\Models\Invoice;
use App\Models\Reminder;
use App\Modules\Invoicing\Factory\ReminderFactory;
use App\Modules\Invoicing\Services\ReminderGenerationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GeneratePaymentReminders extends Command
{
    const BATCH_SIZE = 50;

    /** @var string */
    protected $signature = 'generate-paymentReminders';

    /** @var string  */
    protected $description = 'generate payment reminders such as emails, sms, notifications';

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $invoice = new Invoice();
        $totalInvoices = $invoice->getUnpaidInvoices(true)[0] ?? 0;

        if ($totalInvoices == 0){
            Log::info('No more unpaid invoices to generate EMAILS, SMS & NOTIFICATIONS ');
            exit(0);
        }

        $upper = 0;
        // process in batches
        while ($upper < $totalInvoices) {
            $invoices = $invoice->getUnpaidInvoices(false, $upper, static::BATCH_SIZE);

            if (empty($invoices)) {
                Log::info('No more unpaid invoices to generate EMAILS, SMS & NOTIFICATIONS ');
                exit(0);
            }
            $reminders = $this->generateReminders($invoices);
            $this->saveToDatabase($reminders);

            $upper += static::BATCH_SIZE;
        }
    }

    /**
     * @param array $reminders
     */
    protected function saveToDatabase(array $reminders): void
    {
        $emails = $reminders[Constants::CHANNEL_EMAIL] ?? [];
        $sms = $reminders[Constants::CHANNEL_SMS] ?? [];
        $notifications = $reminders[Constants::CHANNEL_NOTIFICATION] ?? [];

        $reminderFactory = new ReminderFactory();

        $this->saveRemindersByType($reminderFactory, $emails, Constants::CHANNEL_EMAIL);
        $this->saveRemindersByType($reminderFactory, $sms, Constants::CHANNEL_SMS);
        $this->saveRemindersByType($reminderFactory, $notifications, Constants::CHANNEL_NOTIFICATION);
    }

    /**
     * @param ReminderFactory $reminderFactory
     * @param array $reminders
     * @param string $type
     */
    protected function saveRemindersByType(ReminderFactory $reminderFactory, array $reminders, string $type): void
    {
        if (!empty($reminders)) {
            /** @var Reminder $reminder */
            $reminder = $reminderFactory->getReminderByChannel($type);
            $reminder::insert($reminders);
        }
    }

    /**
     * @param array $invoices
     * @return array
     */
    public function generateReminders(array $invoices): array
    {
        $reminderGenerator = new ReminderGenerationService($invoices);
        return $reminderGenerator->populateReminders();
    }
}