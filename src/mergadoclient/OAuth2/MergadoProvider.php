<?php

namespace MergadoClient\OAuth2;


use League\OAuth2\Client\Grant\AbstractGrant;
use League\OAuth2\Client\Provider\AbstractProvider;
use League\OAuth2\Client\Provider\ResourceOwnerInterface;
use League\OAuth2\Client\Token\AccessToken;
use MergadoClient\Exception\UnauthorizedException;
use Psr\Http\Message\ResponseInterface;

class MergadoProvider extends AbstractProvider
{

    const BASEURL = 'https://app.mergado.com/oauth2';
    const BASEURL_DEV = 'https://app.mergado.com/oauth2';
    const BASEURL_LOCAL = 'http://dev.mergado.com/oauth2';

    public function __construct(array $options = [], array $collaborators = [], $mode = null)
    {
        $this->assertRequiredOptions($options);

        foreach ($options as $key => $value) {
            $this->$key = $value;
        }

        $this->mode = $mode;

        parent::__construct($options, $collaborators);
    }

    /**
     * A toggle to enable the dev tier URL's.
     *
     * @var boolean
     */
    protected $mode = false;


    /**
     * Returns the base URL for authorizing a client.
     *
     * Eg. https://oauth.service.com/authorize
     *
     * @return string
     */
    public function getBaseAuthorizationUrl()
    {
        return $this->getBaseMergadoUrl() . '/authorize';
    }

    /**
     * Returns the base URL for requesting an access token.
     *
     * Eg. https://oauth.service.com/token
     *
     * @param array $params
     * @return string
     */
    public function getBaseAccessTokenUrl(array $params)
    {
        return $this->getBaseMergadoUrl() . '/token';
    }

    /**
     * @return boolean
     */
    public function isMode()
    {
        return $this->mode;
    }

    /**
     * @param boolean $mode
     */
    public function setMode($mode)
    {
        $this->mode = $mode;
    }

    /**
     * Returns the default scopes used by this provider.
     *
     * This should only be the scopes that are required to request the details
     * of the resource owner, rather than all the available scopes.
     *
     * @return array
     */
    protected function getDefaultScopes()
    {

    }

    /**
     * Checks a provider response for errors.
     *
     * @throws IdentityProviderException
     * @param  ResponseInterface $response
     * @param  array|string $data Parsed response data
     * @return void
     */
    protected function checkResponse(ResponseInterface $response, $data)
    {

    }

    /**
     * Get the base Facebook URL.
     *
     * @return string
     */
    private function getBaseMergadoUrl()
    {
        if ($this->mode == 'dev') {
            return static::BASEURL_DEV;
        } else if ($this->mode == 'local') {
            return static::BASEURL_LOCAL;
        } else {
            return static::BASEURL;
        }
    }

    /**
     * Verifies that all required options have been passed.
     *
     * @param  array $options
     * @return void
     * @throws InvalidArgumentException
     */
    private function assertRequiredOptions(array $options)
    {
        $missing = array_diff_key(array_flip($this->getRequiredOptions()), $options);

        if (!empty($missing)) {
            throw new \InvalidArgumentException(
                'Required options not defined: ' . implode(', ', array_keys($missing))
            );
        }
    }

    /**
     * Returns all options that are required.
     *
     * @return array
     */
    protected function getRequiredOptions()
    {
        return [
            'clientId',
            'clientSecret',
            'redirectUri',
        ];
    }

    /**
     * Returns authorization parameters based on provided options.
     *
     * @param  array $options
     * @return array Authorization parameters
     */
    protected function getAuthorizationParameters(array $options)
    {
//        I removed this from application authorization flow
//        if (empty($options['state'])) {
//            $options['state'] = $this->getRandomState();
//        }

//        if (empty($options['scope'])) {
//            $options['scope'] = $this->getDefaultScopes();
//        }

        if (empty($options['entity_id'])) {
            $options['entity_id'] = '';
        }

        $options += [
            'response_type' => 'code',
            'approval_prompt' => 'auto'
        ];

//        if (is_array($options['scope'])) {
//            $separator = $this->getScopeSeparator();
//            $options['scope'] = implode($separator, $options['scope']);
//        }

        // Store the state as it may need to be accessed later on.
//        $this->state = $options['state'];

        return [
            'client_id' => $this->clientId,
            'redirect_uri' => $this->redirectUri,
            'entity_id' => $options['entity_id'],
//            'state' => $this->state,
//            'scope' => $options['scope'],
            'response_type' => $options['response_type'],
            'approval_prompt' => $options['approval_prompt'],
        ];
    }

    /**
     * Requests an access token using a specified grant and option set.
     *
     * @param  mixed $grant
     * @param  array $options
     * @return AccessToken
     */
    public function getAccessToken($grant, array $options = [])
    {
        if($grant == 'offline_token') {
            $grant = 'refresh_token';
            if(isset($options["entity_id"])) {
                $options["refresh_token"] = base64_encode($options["entity_id"]);
                unset($options["entity_id"]);
            }
        } elseif ($grant == 'refresh_token') {
            if(isset($options["entity_id"])) {
                $options["refresh_token"] = base64_encode($options["entity_id"]);
                unset($options["entity_id"]);
            }
        }

        $grant = $this->verifyGrant($grant);

        $params = [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
        ];

        $params = $grant->prepareRequestParameters($params, $options);
        $request = $this->getAccessTokenRequest($params);
        $response = $this->getResponse($request);
        if (gettype($response) == "string") {
            throw new UnauthorizedException("Request error. Didn't get json response back.", $request);
        }
        $prepared = $this->prepareAccessTokenResponse($response);
        $token = $this->createAccessToken($prepared, $grant);

        return $token;
    }

    /**
     * Creates an access token from a response.
     *
     * The grant that was used to fetch the response can be used to provide
     * additional context.
     *
     * @param  array $response
     * @param  AbstractGrant $grant
     * @return AccessToken
     */
    protected function createAccessToken(array $response, AbstractGrant $grant)
    {
        return new AccessToken($response);
    }


    /**
     * Returns the URL for requesting the resource owner's details.
     *
     * @param AccessToken $token
     * @return string
     */
    public function getResourceOwnerDetailsUrl(AccessToken $token)
    {
        // TODO: Implement getResourceOwnerDetailsUrl() method.
    }

    /**
     * Generates a resource owner object from a successful resource owner
     * details request.
     *
     * @param  array $response
     * @param  AccessToken $token
     * @return ResourceOwnerInterface
     */
    protected function createResourceOwner(array $response, AccessToken $token)
    {
        // TODO: Implement createResourceOwner() method.
    }
}