<?php

namespace MobileCart\MailChimpBundle\EventListener\Checkout;

use MobileCart\CoreBundle\Event\CoreEvent;

class OrderSubmitSuccess
{
    protected $mailchimpService;



    public function onOrderSubmitSuccess(CoreEvent $event)
    {
        $cart = $event->get('cart');
        $order = $event->get('order');


    }
}
