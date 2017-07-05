<?php

namespace Djaxho\LaravelInfusionsoftOauth2;

use Infusionsoft\Token;
use Illuminate\Database\Query\Builder;

class TokenManager
{
    /**
     * This is the builder pulled in from Laravel.
     * You can refer to the documentation here:
     * http://laravel.com/docs/master/queries
     * @var Illuminate\Database\Query\Builder
     */
    protected $builder;

    /**
     * @param Builder $builder
     */
    public function __construct(Builder $builder)
    {
        $this->setBuilder($builder);
    }

    /**
     * @param $builder
     */
    public function setBuilder($builder)
    {
        $this->builder = $builder;
    }

    /**
     * @return Illuminate\Database\Query\Builder
     */
    public function getBuilder()
    {
        return $this->builder;
    }

    /**
     * Takes the token object and converts it to an array that can be used
     * with database interactions
     * @param Token $token
     * @return array
     */
    public function processToken(Token $token)
    {
        $tokenData = array(
            'access_token' => $token->getAccessToken(),
            'refresh_token' => $token->getRefreshToken(),
            'expires_in' => $token->getEndOfLife(),
            'extra_data' => serialize($token->getExtraInfo())
        );

        return $tokenData;
    }

    /**
     * @param $token
     * @return mixed
     */
    public function insertToken($token)
    {
        // Process the token
        $tokenData = $this->processToken($token);

        // Insert token data into storage using the builder
        return $this->builder->insert($tokenData);
    }

    /**
     * @param $token
     */
    public function updateToken($token)
    {
        // Process the token
        $tokenData = $this->processToken($token);

        // Update the token found in the DB
        $this->builder->update($tokenData);
    }

    /**
     * @return Token|null
     */
    public function retrieveToken()
    {
        $tokenData = $this->builder->first();

        if ($tokenData) {
            $token = new Token();
            $token->setAccessToken($tokenData->access_token);
            $token->setrefreshToken($tokenData->refresh_token);
            $token->setEndOfLife($tokenData->expires_in);
            $token->setExtraInfo(unserialize($tokenData->extra_data));

            return $token;
        } else {
            return null;
        }
    }

    /**
     * @return mixed
     */
    public function deleteToken()
    {
        return $this->builder->truncate();
    }
}
