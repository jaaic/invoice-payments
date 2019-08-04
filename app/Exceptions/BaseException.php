<?php

namespace App\Exceptions;

use Exception;

/**
 * Class BaseException
 *
 * @package App\Exceptions
 * @author  Jaai Chandekar
 */
abstract class BaseException extends Exception
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $status;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $detail;

    /**
     * @var string
     */
    protected $trace;

    /** @var string */
    protected $type;

    /** @var string */
    protected $responseState = 'error';

    /**
     * Get the status
     *
     * @return int
     */
    public function getStatus(): int
    {
        return (int)$this->status;
    }

    /**
     * BaseException constructor.
     *
     * @param string $detail
     * @param string $title
     * @param string $trace
     */
    public function __construct($detail, $title = '', $trace = '')
    {
        $this->detail = $detail ?: $this->detail;
        $this->title  = $title ?: $this->title;
        $this->trace  = $trace ?: $this->trace;


        parent::__construct($this->detail);
    }

    /**
     * Return the Exception as an array
     *
     * @return array
     */
    public function toArray()
    {
        return array_filter([
            'status'        => $this->status,
            'title'         => $this->title,
            'detail'        => $this->detail,
            'type'          => !empty($this->type) ? $this->type : 'https://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html',
            'trace'         => $this->trace,
            'responseState' => $this->responseState,
        ]);
    }

    /**
     * Get response state
     *
     * @return string
     */
    public function getResponseState(): string
    {
        return $this->responseState;
    }
}