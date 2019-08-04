<?php

namespace App\Http\Controllers;

use App\Modules\Invoicing\Request\NotificationStatsRequest;
use Illuminate\Http\Request;

class NotificationStatsController extends Controller
{
    /** @var \Illuminate\Http\Request */
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Make transfer request
     *
     * @throws \Exception
     */
    public function getStats(): array
    {
        $request = new NotificationStatsRequest();

        $response = $request->load($this->request->all())
            ->validate()
            ->process();

        return $response;
    }

}

