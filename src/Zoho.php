<?php

namespace Postsmtp\OAuth2\Client\Provider;

use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Token\AccessToken;
use Psr\Http\Message\ResponseInterface;

/**
 * Class Zoho
 *
 * @author Yehuda Hassine <yehuda@myinbox.in>
 *
 * @package PostSmtp\OAuth2\Client\Provider
 */
class Zoho extends AbstractProvider
{
    /**
     * Returns the base URL for authorizing a client.
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return 'https://accounts.zoho.com/oauth/v2/auth';
    }

    /**
     * Returns the base URL for requesting an access token.
     *
     * @param array $params
     *
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return 'https://accounts.zoho.com/oauth/v2/token';
    }

    /**
     * Returns the URL for requesting the resource owner's details.
     *
     * @param AccessToken $token
     *
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        return 'https://accounts.zoho.com/oauth/user/info';
    }

    /**
     * @return array
     */
    protected function getDefaultScopes()
    {
        return ['aaaserver.profile.READ', 'ZohoProfile.userinfo.read', 'ZohoProfile.userphoto.read'];
    }

    /**
     * Checks a provider response for errors.
     *
     * @param ResponseInterface $response
     * @param array|string $data Parsed response data
     *
     * @return \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {
        if ($response->getStatusCode() !== 200 || isset($data['data']['errorCode'])) {

            throw new IdentityProviderException(
                sprintf('There was an error on response: %s', $data['data']['errorCode']),
                $response->getStatusCode(),
                $data['data']['status']['description']
            );
        }
    }

    protected function getAuthorizationHeaders($token = null)
    {
        return ["Authorization" => "Zoho-oauthtoken {$token->getToken()}"];
    }

    /**
     * Create new resources owner using the generated access token.
     *
     * @param array $response
     * @param AccessToken $token
     *
     * @return ZohoResourceOwner
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        return new ZohoResourceOwner($response);
    }
}