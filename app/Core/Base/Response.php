<?php

namespace App\Core\Base;

/**
 * Class Response
 *
 * @package App\Core\Base
 * @author  Jaai Chandekar
 */
abstract class Response
{
    /**
     * @var array $attributes response attributes
     */
    protected $responseValues = [];

    /**
     * Returns attributes array on classes that extend Response
     *
     * @return array
     */
    abstract public function attributes(): array;

    /**
     * Response constructor.
     *
     * @param mixed $data Data passed to the response
     */
    public function __construct($data = [])
    {
        $this->responseValues = $data;
    }

    /**
     * @return array
     */
    public function transform(): array
    {
        $projectAttributes = $this->attributes();
        $response          = [];
        foreach ($this->responseValues as $key => $value) {
            if (in_array($key, $projectAttributes)) {
                $response[$key] = $value;
            }
        }

        return $response;
    }
}