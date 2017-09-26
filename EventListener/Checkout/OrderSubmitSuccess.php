<?php

namespace MobileCart\MailChimpBundle\EventListener\Checkout;

use MobileCart\CoreBundle\Event\CoreEvent;

/**
 * Class OrderSubmitSuccess
 * @package MobileCart\MailChimpBundle\EventListener\Checkout
 */
class OrderSubmitSuccess
{
    /**
     * @var \MobileCart\MailChimpBundle\Service\MailChimpService
     */
    protected $mailchimpService;

    /**
     * @param \MobileCart\MailChimpBundle\Service\MailChimpService $mailChimpService
     * @return $this
     */
    public function setMailChimpService(\MobileCart\MailChimpBundle\Service\MailChimpService $mailChimpService)
    {
        $this->mailchimpService = $mailChimpService;
        return $this;
    }

    /**
     * @return \MobileCart\MailChimpBundle\Service\MailChimpService
     */
    public function getMailChimpService()
    {
        return $this->mailchimpService;
    }

    /**
     * @param CoreEvent $event
     */
    public function onOrderSubmitSuccess(CoreEvent $event)
    {
        if (!$this->getMailChimpService()->getIsEnabled()) {
            return;
        }

        $order = $event->get('order');

        $email = $order->getEmail();
        $firstname = '';
        $lastname = '';

        $customer = $order->getCustomer();

        if ($customer) {
            $firstname = $customer->getFirstName();
            $lastname = $customer->getLastName();
        }

        $memberId = $this->getMailChimpService()->findMemberId($email);
        if (!strlen($memberId)) {
            $this->getMailChimpService()->addMember($email, $firstname, $lastname);
        }
    }
}
