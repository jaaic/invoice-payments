<?php

namespace App\Modules\Invoicing\Response;

use App\Core\Base\Response;

/**
 * Class ErrorResponse
 *
 * @package App\Modules\Transactions\Response
 */
class ErrorResponse extends Response
{
    /**
     * Response attributes
     *
     * @return array
     */
    public function attributes(): array
    {
        return [
            'status',
            'title',
            'detail',
            'type',
        ];
    }
}
