<?php

namespace Excent\BePaidLaravel\Http\Controllers;

use BeGateway\Webhook;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Excent\BePaidLaravel\Contracts\BePaidSubscriber;

class BePaidController extends Controller
{
    private $webhook;

    public function __construct(Webhook $webhook)
    {
        $this->webhook = $webhook;
    }

    public function notification(Request $request)
    {
        $event = $this->webhook->isAuthorized() ?
            BePaidSubscriber::EVENT_NOTIFICATION_SUCCESS :
            BePaidSubscriber::EVENT_NOTIFICATION_FAIL;

        return event($event, [$request], true);
    }

    public function success(Request $request)
    {
        return event(BePaidSubscriber::EVENT_SUCCESS_URL, [$request], true);
    }

    public function fail(Request $request)
    {
        return event(BePaidSubscriber::EVENT_FAIL_URL, [$request], true);
    }

    public function decline(Request $request)
    {
        return event(BePaidSubscriber::EVENT_DECLINE_URL, [$request]);
    }

    public function cancel(Request $request)
    {
        return event(BePaidSubscriber::EVENT_CANCEL_URL, [$request]);
    }

    public function return(Request $request)
    {
        return event(BePaidSubscriber::EVENT_RETURN_URL, [$request]);
    }
}
