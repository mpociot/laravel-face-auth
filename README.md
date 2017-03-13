# Laravel face authentication

This package uses Microsoft's cognitive API to identify faces instead of passwords for your Laravel application.


## Installation

You can install the package via composer:

``` bash
composer require mpociot/laravel-face-auth
```

## Usage

Add the service provider to your `config/app.php`:

``` php
Mpociot\FaceAuth\FaceAuthServiceProvider::class,
```

In your `config/auth.php`, change the auth driver to `faceauth`:

```php
'providers' => [
    'users' => [
        'driver' => 'faceauth',
        'model' => App\User::class,
    ],
]
```

Publish the configuration:
``` bash
php artisan vendor:publish --provider="Mpociot\FaceAuth\FaceAuthServiceProvider"
```
Edit the newly published `config/faceauth.php` file and enter your [Face API key](https://www.microsoft.com/cognitive-services/en-us/face-api).

Next, your `User` model needs to implement the `FaceAuthenticatable` interface, which this package provides.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email freek@mpociot.be instead of using the issue tracker.

## Credits

- [Marcel Pociot](https://github.com/mpociot)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
