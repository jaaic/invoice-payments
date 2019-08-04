<?php

namespace App\Modules\Invoicing\Request;

use App\Core\Base\Request;
use App\Core\Constants;
use App\Modules\Invoicing\Response\NotificationStatsResponse;
use App\Modules\Invoicing\Services\NotificationStatsService;
use Illuminate\Support\Facades\Log;
use App\Modules\Invoicing\Response\ErrorResponse as NotificationErrorResponse;

class NotificationStatsRequest extends Request
{
    /**
     * Expected Attributes of the request payload
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'account_number',
            'past_days',
            'next_days',
        ];

    }

    /**
     * Validation of the request payload
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'account_number' => 'required|string',
            'past_days' => 'integer|nullable',
            'next_days' => 'integer|nullable',
        ];
    }

    /**
     * Process request
     *
     * @return array
     */
    public function process(): array
    {
        // check validation errors
        if (!empty($this->getErrors())) {
            $errors = $this->getErrors();
            Log::error(json_encode($errors));

            return (new NotificationErrorResponse($errors))->transform();
        }

        if (null == $this->getAttribute('past_days')) {
            $this->setAttribute('past_days', Constants::NOTIFICATION_STATS_DEFAULT_PAST_DAYS);
        }

        if (null == $this->getAttribute('next_days')) {
            $this->setAttribute('next_days', Constants::NOTIFICATION_STATS_DEFAULT_NEXT_DAYS);
        }

        // call service
        $service = new NotificationStatsService($this->getAttributes());
        $response = $service->process();
        return (new NotificationStatsResponse($response))->transform();
    }
}
