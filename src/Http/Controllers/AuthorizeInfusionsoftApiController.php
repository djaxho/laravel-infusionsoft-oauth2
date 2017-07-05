<?php

namespace Djaxho\LaravelInfusionsoftOauth2\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class AuthorizeInfusionsoftApiController extends BaseController
{
    public function auth()
    {
        if (! (config('laravel-infusionsoft-oauth2.ISDK_API_HOST') && config('laravel-infusionsoft-oauth2.ISDK_API_CLIENTID') && config('laravel-infusionsoft-oauth2.ISDK_API_CLIENTSECRET') && config('laravel-infusionsoft-oauth2.ISDK_API_REDIRECT'))) {
            dd('check config files or env variables for infusion api');
        }

        return view('laravel-infusionsoft-oauth2::authorize');
    }
}