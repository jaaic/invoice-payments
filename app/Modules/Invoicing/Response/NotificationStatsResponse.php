<?php

namespace App\Modules\Invoicing\Response;

use App\Core\Base\Response;

/**
 * Class TransferResponse
 *
 * @package App\Modules\Transactions\Response
 */
class NotificationStatsResponse extends Response
{
    /**
     * Response attributes
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'past',
            'upcoming'
        ];
    }
}
