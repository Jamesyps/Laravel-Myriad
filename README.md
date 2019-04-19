# Laravel Myriad

A simple pattern library package to document and display blade components.

![License](https://img.shields.io/github/license/Jamesyps/Laravel-Myriad.svg)
![Latest Release](https://img.shields.io/github/release/Jamesyps/Laravel-Myriad.svg)
![PHP Version Support](https://img.shields.io/packagist/php-v/jamesyps/laravel-myriad.svg)
![Build Status](https://img.shields.io/travis/Jamesyps/Laravel-Myriad.svg)

## Installation

Install via Composer:

```
composer require jamesyps/laravel-myriad
```

Publish assets:

```
php artisan vendor:publish --tag myriad-assets
```

You can then visit the pattern library at `https://your-app.xxx/myriad/components`

## Customisation

Myriad has been developed to be full configurable to the needs of your application and workflow. 

### Config

Basic customisation can be made through the config file. Use the following command to publish it:

```
php artisan vendor:publish --tag myriad-config
```

To see what options are available you can view the [source code here](./config/myriad.php).

### Templates

To modify the default templates publish them with the following command:

```
php artisan vendor:publish --tag myriad-views
```

You can then find them in `resources/views/vendor/myriad`.

### Contracts

If you need to add or modify functionality for your app, you can swap out the default Component Repository and Component Model classes with your own.

```php
[
    'model' => \App\Components\Models\Component::class,
    'repository' => \App\Components\Repositories\ComponentRepository::class,
]
```

If you are not extending the original classes, you must ensure that any new code adheres to the following contracts:

```
Jamesyps\Myriad\Contracts\ComponentRepositoryInterface
Jamesyps\Myriad\Contracts\ComponentInterface
```

These can be found in the [Contracts](./src/Contracts) namespace.
