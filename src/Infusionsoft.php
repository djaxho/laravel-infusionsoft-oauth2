<?php

namespace Djaxho\LaravelInfusionsoftOauth2;

use Infusionsoft\Infusionsoft as ISDK;
use Infusionsoft\Token;

/**
 * Class Infusionsoft
 * @package App\Lib\Infusionsoft
 *
 * Based on the original Infusionsoft API Plugin written by Danny Jackson <djaxho@gmail.com>
 */
class Infusionsoft extends ISDK
{

    /**
     * @var TokenManager
     */
    protected $tokenManager;

    /**
     * @param array $config
     */
    public function __construct($config = array())
    {
        if (isset($config['clientId'])) $this->clientId = $config['clientId'];
        if (isset($config['clientSecret'])) $this->clientSecret = $config['clientSecret'];
        if (isset($config['redirectUri'])) $this->redirectUri = $config['redirectUri'];
        if (isset($config['tokenTable'])) $this->tokenTable = $config['tokenTable'];
        if (isset($config['debug'])) $this->debug = $config['debug'];
    }

    /**
     * @param $tokenManager
     */
    public function setTokenManager($tokenManager)
    {
        $this->tokenManager = $tokenManager;
    }

    /**
     * @return TokenManager
     */
    public function getTokenManager()
    {
        return $this->tokenManager;
    }

    /**
     * @param $token
     */
    public function insertToken($token)
    {
        $this->tokenManager->insertToken($token);
    }

    /**
     * @param $token
     */
    public function updateToken($token)
    {
        $this->tokenManager->updateToken($token);
    }

    /**
     * Returns the token from the token manager
     * @return mixed
     */
    public function retrieveToken()
    {
        return $this->tokenManager->retrieveToken();
    }

    /**
     * Sets the $token to the $token property, and also
     * inserts or updates the token via the tokenManager
     * @throws InvalidTokenException;
     * @param Token $token
     */
    public function setToken($token)
    {
        // Check to see that the $token is an actual Infusionsoft\Token
        if (is_a($token, 'Infusionsoft\Token')) {
            // Set the token to the property
            $this->token = $token;

            // Attempt to retrieve a token from the database
            $retrievedToken = $this->retrieveToken();

            if ($retrievedToken) {
                $this->tokenManager->updateToken($token);
            } else {
                $this->tokenManager->insertToken($token);
            }

        } else {
            throw new InvalidTokenException;
        }
    }

    /**
     * Either returns the token stored as a property
     * @return bool|Token
     */
    public function getToken()
    {
        if (isset($this->token)) {
            return $this->token;
        } else {
            return false;
        }
    }

    /**
     * Unsets the token property and deletes the token
     * via the tokenManager
     */
    public function unsetToken()
    {
        unset($this->token);
        $this->tokenManager->deleteToken();
    }

    /**
     * Checks if we have a token stored in property or via
     * tokenManager
     * @return bool
     * @throws InvalidTokenException
     */
    public function hasToken()
    {
        // First check if we have token set as a property...
        if (!$this->getToken()) {

            // Attempt to retrieve a token from the database
            $retrievedToken = $this->retrieveToken();

            // if we did not have a token, return false
            // else, set the retrievedToken and return true
            if (!$retrievedToken) {
                return false;
            } else {
                $this->setToken($retrievedToken);
                return true;
            }
        } else {
            return true;
        }
    }

    /**
     * @param string $code
     * @return array
     * @throws InfusionsoftException
     */
    public function requestAccessToken($code, $debug = null)
    {
        if (is_null($debug)) {
            $params = array(
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code'          => $code,
                'grant_type'    => 'authorization_code',
                'redirect_uri'  => $this->redirectUri,
            );

            $client = $this->getHttpClient();

            $tokenInfo = $client->request($this->tokenUri, $params, array(), 'POST');
        } else {
            $tokenInfo = array(
                'access_token' => $code,
                'refresh_token' => $code,
                'expires_in' => 18800,
                'type' => 'bearer'
            );
        }

        $this->setToken(new Token($tokenInfo));

        return $this->getToken();
    }

    /**
     * @throws MissingTokenException
     * @return mixed
     */
    public function request()
    {
        // Before making the request, we can make sure that the token is still
        // valid by doing a check to see if it exists
        if ($this->hasToken()) {
            $token = $this->getToken();

            // Then we'll check to see if the token is expired
            if ($token->getEndOfLife() < time()) {
                $this->refreshAccessToken();
            }

            $url = $this->url . '?' . http_build_query(array('access_token' => $token->getAccessToken()));

            $params = func_get_args();
            $method = array_shift($params);

            // Some older methods in the API require a key parameter to be sent
            // even if OAuth is being used. This flag can be made false as it
            // will break some newer endpoints.
            if ($this->needsEmptyKey)
            {
                $params = array_merge(array('key' => $token->getAccessToken()), $params);
            }

            // Reset the empty key flag back to the default for the next request
            $this->needsEmptyKey = true;

            $client = $this->getSerializer();
            $response = $client->request($url, $method, $params, $this->getHttpClient());

            return $response;
        } else {
            throw new MissingTokenException;
        }
    }

    /**
     * Prints the stored token's information in a serialized format
     * @return string
     */
    public function printToken() {
        if ($this->hasToken()) {
            return serialize($this->token);
        } else {
            return false;
        }
    }
}