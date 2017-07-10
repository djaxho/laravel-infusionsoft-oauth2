# laravel-infusionsoft-oauth2

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]


Laravel package to facilitate the use of the Infusionsoft API using OAUTH2. This packages is quite specific to laravel

## Install

Via Composer

``` bash
$ composer require djaxho/laravel-infusionsoft-oauth2
```

## Installation
(These instructions are for an 'alerady set-up' laravel project with a functioning database set up)
Add the service provider for laravel by adding the following line to your 'providers' array in the file congig/app.php 
``` php
Djaxho\LaravelInfusionsoftOauth2\LaravelInfusionsoftOauth2ServiceProvider::class,
```
Publish the package files to your project by running:
``` bash
$ php artisan vendor:publish
```
Add the following variable to your .env file or u them in your pdate config/laravel-infusionsoft-oauth2.php file with values obtained from your infusionsoft developer account and for ISDK_API_REDIRECT use the uri you are serving your site from (i.e. www.yoursite.dev)
``` bash
ISDK_API_HOST
ISDK_API_CLIENTID
ISDK_API_CLIENTSECRET
ISDK_API_REDIRECT
```

Run your database migrations (first make sure you have your database info in your .env file or config files so you can successfully connect to the database of your choosing):
``` bash
$ php artisan migrate
```
You may now navigate to your.url/authorize-infusionsoft-api (whichever url you used for ISDK_API_REDIRECT) and click 'authorize.' You will then be taken to infusionsoft to authorize your api connection. At the end of this process you will be led back to the your.url/authorize-infusionsoft-api page with a success message and the oauth2 key stored in your database

## Usage
To start using the infusionsoft api after going through the steps above, add this to the top of your php file:
``` php
use Djaxho\LaravelInfusionsoftOauth2\Infusionsoft;
```
And in the methods of your classes (or in the constructor method) you can leverage laravel's automatic dependency injection to instantiate the infusionsoft api singleton. As an example of something you could do to test your api connection in a controller class:
``` php

protected $infusionsoft;

public function __construct(Infusionsoft $infusionsoft)
{
    $this->infusionsoft = $infusionsoft;
    var_dump($this->infusionsoft->hasToken());
}
```
Then you can use the infusionsoft api documentation to make api calls. As a simple example, you would use it liek this:
``` php
$updateField = $this->infusionsoft->data()->update('Contact', $contactId, $updateData);
```

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security

If you discover any security related issues, please email djaxho@gmail.com instead of using the issue tracker.

## Credits

- [Danny Jackson][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/djaxho/laravel-infusionsoft-oauth2.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/djaxho/laravel-infusionsoft-oauth2/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/djaxho/laravel-infusionsoft-oauth2.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/djaxho/laravel-infusionsoft-oauth2.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/djaxho/laravel-infusionsoft-oauth2.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/djaxho/laravel-infusionsoft-oauth2
[link-travis]: https://travis-ci.org/djaxho/laravel-infusionsoft-oauth2
[link-scrutinizer]: https://scrutinizer-ci.com/g/djaxho/laravel-infusionsoft-oauth2/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/djaxho/laravel-infusionsoft-oauth2
[link-downloads]: https://packagist.org/packages/djaxho/laravel-infusionsoft-oauth2
[link-author]: https://github.com/djaxho
[link-contributors]: ../../contributors
