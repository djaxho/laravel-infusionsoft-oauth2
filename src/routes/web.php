<?php 

Route::get('/authorize-infusionsoft-api', 'Djaxho\LaravelInfusionsoftOauth2\Http\Controllers\AuthorizeInfusionsoftApiController@auth')->name('authorize-infusionsoft-api');