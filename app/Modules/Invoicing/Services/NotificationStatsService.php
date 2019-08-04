<?php


namespace App\Modules\Invoicing\Services;


use App\Core\Constants;
use App\Models\Reminder;
use App\Modules\Invoicing\Factory\ReminderFactory;

class NotificationStatsService
{
    protected $accountNr;
    protected $pastDays;
    protected $nextDays;

    /** @var Reminder */
    protected $emailModel;

    /** @var Reminder */
    protected $smsModel;

    /** @var Reminder */
    protected $notificationModel;

    public function __construct(array $attributes)
    {
        $this->accountNr = $attributes['account_number'];
        $this->nextDays = $attributes['next_days'];
        $this->pastDays = $attributes['past_days'];
    }

    public function process(): array
    {
        $reminderFactory = new ReminderFactory();
        $this->emailModel = $reminderFactory->getReminderByChannel(Constants::CHANNEL_EMAIL);
        $this->smsModel = $reminderFactory->getReminderByChannel(Constants::CHANNEL_SMS);
        $this->notificationModel = $reminderFactory->getReminderByChannel(Constants::CHANNEL_NOTIFICATION);

        $pastNotifications = $this->getTriggeredNotifications();
        $upcomingNotifications = $this->getUpcomingNotifications();

        return [
            'past' => $pastNotifications,
            'upcoming' => $upcomingNotifications
        ];
    }

    protected function getTriggeredNotifications(): array
    {
        $triggeredEmails = $this->emailModel->getTriggeredReminders($this->accountNr, $this->pastDays);
        $triggeredSms = $this->smsModel->getTriggeredReminders($this->accountNr, $this->pastDays);
        $triggeredNotifications = $this->notificationModel->getTriggeredReminders($this->accountNr, $this->pastDays);

        return [
            Constants::CHANNEL_EMAIL => $triggeredEmails,
            Constants::CHANNEL_SMS => $triggeredSms,
            Constants::CHANNEL_NOTIFICATION => $triggeredNotifications
        ];
    }

    protected function getUpcomingNotifications(): array
    {
        $upcomingEmails = $this->emailModel->getUpcomingReminders($this->accountNr, $this->nextDays);
        $upcomingSms = $this->smsModel->getUpcomingReminders($this->accountNr, $this->nextDays);
        $upcomingNotifications = $this->notificationModel->getUpcomingReminders($this->accountNr, $this->nextDays);

        return [
            Constants::CHANNEL_EMAIL => $upcomingEmails,
            Constants::CHANNEL_SMS => $upcomingSms,
            Constants::CHANNEL_NOTIFICATION => $upcomingNotifications
        ];
    }
}
