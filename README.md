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

## Components

The primary purpose of this package is to document the components within a Laravel project. For more information on how components can be used, see the [Laravel documentation](https://laravel.com/docs/5.8/blade#components-and-slots).

To get started create a new component in the configured components directory, in this case let's start with a button:

```blade
<button class="btn btn-{{ $type ?? 'default'}}">
	{{ $slot }}
</button>
```

Now it needs some documentation to show others how it can be used, what slots are available and what attributes can be passed through. This can be done with a blade comment and yaml front matter:

```blade
{{--

---
slots:
    - default: My Button
variables:
    - type: primary
---

--}}

<button class="btn btn-{{ $type ?? 'default'}}">
	{{ $slot }}
</button>
```

Within the preview view two buttons will now appear, one with the class `btn-default` and another with a class of `btn-primary`. Each one will have the text of _My Button_.

The front matter supports custom properties too which will be visible on the preview screen, for example if you wanted to show the current status or browser support.

You can also provide further information by adding text below the last `---`. There is full markdown support within this section so you can provide nicely formatted documentation like so:

```blade
{{--

---
slots:
    - default: My Button
variables:
    - type: primary
---

# Instructions

This is a button and should be used when an interaction is required. 
**Do not** use this for navigation, instead use an `<a>` tag for this.

--}}

<button class="btn btn-{{ $type ?? 'default'}}">
	{{ $slot }}
</button>
```

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
