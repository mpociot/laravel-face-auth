# Laravel Face authentication

This package uses Microsoft's cognitive API to identify faces instead of passwords for your Laravel application.


## Installation

You can install the package via composer:

``` bash
composer require mpociot/laravel-face-auth
```

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

## Usage and authorization

The face authentication works, by using a reference image of your user and matching it against a uploaded image upon login.
So this pretty much is the same flow as comparing two password hashes.

When you register your users, you need to make sure that you store a photo of the users face - this is basically his password.

In order for this package, to find the user photo, your `User` model needs to implement the `FaceAuthenticatable` interface.

This interface only has one single method, which is `public function getFaceAuthPhoto()`. This method needs to return the content of the user photo.

Example:

```php
class User extends Authenticatable implements FaceAuthenticatable
{

	public function getFaceAuthPhoto()
	{
		return File::get(storage_path('facces').$this->id.'.png');
	}

}
```

Your login form now needs a `photo` field (the name can be configured) - this field should contain a base64 representation of the image, the user uses to log in.

> If you want a simple way to capture the user image from the webcam, take a look at the [vue-webcam](https://github.com/smronju/vue-webcam) Vue.js component.

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
