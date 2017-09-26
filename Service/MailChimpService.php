<?php

namespace MobileCart\MailChimpBundle\Service;

/**
 * Class MailChimpService
 * @package MobileCart\MailChimpBundle\Service
 */
class MailChimpService
{
    /**
     * @var string
     */
    protected $apiKey = '';

    /**
     * @var string
     */
    protected $customerListId = '';

    /**
     * @param string $apiKey
     * @return $this
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        return $this->apiKey;
    }

    /**
     * @param string $customerListId
     * @return $this
     */
    public function setCustomerListId($customerListId)
    {
        $this->customerListId = $customerListId;
        return $this;
    }

    /**
     * @return string
     */
    public function getCustomerListId()
    {
        return $this->customerListId;
    }

    /**
     * @return \Mailchimp
     */
    public function getClient()
    {
        $svc = new \Mailchimp();
        $svc->setApiKey($this->getApiKey());
        return $svc;
    }

    /**
     * @param $email
     * @param string $firstname
     * @param string $lastname
     * @return $this
     */
    public function addMember($email, $firstname='', $lastname='')
    {
        $params = [
            'email_address' => $email,
            'status' => 'subscribed',
        ];

        if (strlen($firstname)) {
            $params['merge_fields'] = [
                'FNAME' => $firstname,
                'LNAME' => $lastname,
            ];
        }

        $this->getClient()->call("lists/{$this->getCustomerListId()}/members", $params, \Mailchimp::POST);
        return $this;
    }

    /**
     * @param $email
     * @return string
     */
    public function findMemberId($email)
    {
        $listId = $this->getCustomerListId();

        $response = $this->getClient()->call("search-members", [
            'query' => $email,
            'list_id' => $listId
        ], \Mailchimp::GET);

        if (!isset($response['exact_matches'])) {
            return '';
        }

        if (!isset($response['exact_matches']['members'])) {
            return '';
        }

        if (!isset($response['exact_matches']['members'][0])) {
            return '';
        }

        if (!isset($response['exact_matches']['members'][0]['id'])) {
            return '';
        }

        return $response['exact_matches']['members'][0]['id'];
    }

    /**
     * @param $email
     * @return $this
     */
    public function removeMemberByEmail($email)
    {
        $memberId = $this->findMemberId($email);
        if (strlen($memberId)) {
            $this->removeMember($memberId);
        }

        return $this;
    }

    /**
     * @param $memberId
     * @return $this
     */
    public function removeMember($memberId)
    {
        $listId = $this->getCustomerListId();
        $this->getClient()->call("lists/{$listId}/members/{$memberId}", [], \MailChimp::DELETE);
        return $this;
    }
}
