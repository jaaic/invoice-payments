<?php

namespace App\Exceptions;

/**
 * Class BadRequestException
 *
 * @package App\Exceptions
 * @author  Jaai Chandekar
 */
class BadRequestException extends BaseException
{
    /** @var string */
    protected $status = '400';

    /** @var string */
    protected $title = 'Bad Request';

    /** @var string */
    protected $type = 'Client Error';

    /** @var string */
    protected $detail = '';
}