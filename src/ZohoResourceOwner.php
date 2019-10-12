<?php

namespace Postsmtp\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\ResourceOwnerInterface;

class ZohoResourceOwner implements ResourceOwnerInterface
{
    /**
     * @var array
     */
    protected $response;

    /**
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->response = $response['data'][0];
    }

    public function getId()
    {
        return $this->response['ZUID'];
    }

    /**
     * Get preferred display name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->response['Display_Name'];
    }

    /**
     * Get first name.
     *
     * @return string
     */
    public function getFirstName()
    {
        return $this->response['First_Name'];
    }

    /**
     * Get last name.
     *
     * @return string
     */
    public function getLastName()
    {
        return $this->response['Last_Name'];
    }

    /**
     * Get email address.
     *
     * @return string|null
     */
    public function getEmail()
    {
        return $this->response['Email'];
    }

    /**
     * Get user data as an array.
     *
     * @return array
     */
    public function toArray()
    {
        return $this->response;
    }
}