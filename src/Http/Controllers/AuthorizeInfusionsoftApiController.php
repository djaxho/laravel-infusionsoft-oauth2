<?php

namespace Djaxho\LaravelInfusionsoftOauth2\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Djaxho\LaravelInfusionsoftOauth2\Infusionsoft;

class AuthorizeInfusionsoftApiController extends BaseController
{
    protected $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function auth(Infusionsoft $infusionsoft)
    {
        if (! (config('laravel-infusionsoft-oauth2.ISDK_API_HOST') && config('laravel-infusionsoft-oauth2.ISDK_API_CLIENTID') && config('laravel-infusionsoft-oauth2.ISDK_API_CLIENTSECRET') && config('laravel-infusionsoft-oauth2.ISDK_API_REDIRECT'))) {
            dd('check config files or env variables for infusion api');
        }

        if ($infusionsoft->hasToken()) {
            return '<h3>This infusionsoft api connection has already been authorized</h3><p>If the api calls in the app are still failing, you may need to clear the current oauth2 token from the database and come back here to re-authorize the api</p>';
            return $infusionsoft->printToken();
        } else {
            if ($this->request->has('code')) {
                $code = $this->request->input('code');
                $debug = $this->request->input('debug', null);

                $infusionsoft->requestAccessToken($code, $debug);

                return $infusionsoft->printToken();
            }

            return "<a href='".$infusionsoft->getAuthorizationUrl() . "'>Authorize the Infusionsoft Connection</a>";
        }

        return view('laravel-infusionsoft-oauth2::authorize');
    }
}