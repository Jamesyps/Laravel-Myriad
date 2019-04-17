<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | This is the name of the of component library and appears in the header
    | and page title. Use this to define a more descriptive name for the
    | pattern library area.
    |
    */

    'title' => 'Myriad',

    /*
    |--------------------------------------------------------------------------
    | Route
    |--------------------------------------------------------------------------
    |
    | Set the base path where the pattern library can be accessed. These can
    | also be edited by publishing the routes file using Artisan. You are
    | then able to set custom middleware and other larger changes.
    |
    */

    'route' => 'myriad/components',

    /*
    |--------------------------------------------------------------------------
    | Components Directory
    |--------------------------------------------------------------------------
    |
    | Define the path where the component files are located. The directory
    | will be recursively scanned and included in the pattern library to
    | build up the navigation structure.
    |
    */

    'directory' => resource_path('views/components'),

    /*
    |--------------------------------------------------------------------------
    | Responsive Sizes
    |--------------------------------------------------------------------------
    |
    | Different responsive breakpoints can be tested within the component
    | review, the values here are used to set what sizes can be chosen.
    | The key is the UI label, the value sets the width attribute
    |
    */

    'sizes' => [
        'sm' => '320px',
        'md' => '768px',
        'lg' => '1024px',
    ],

    /*
    |--------------------------------------------------------------------------
    | Preview Assets
    |--------------------------------------------------------------------------
    |
    | Components are rendered in isolation from the rest of the application,
    | you may want to therefore include stylesheets and scripts to mimic
    | how they would look when used within your application's UI.
    |
    */

    'preview' => [
        'css' => [

        ],

        'js' => [

        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Model
    |--------------------------------------------------------------------------
    |
    | The component model parses the properties of a component blade file
    | and provides a structured way of accessing this information. You
    | may use a custom class as long as it implements the contract:
    | \Jamesyps\Myriad\Contracts\ComponentInterface
    |
    */

    'model' => \Jamesyps\Myriad\Models\Component::class,

    /*
    |--------------------------------------------------------------------------
    | Repository
    |--------------------------------------------------------------------------
    |
    | The repository scans and organises components and provides methods to
    | query the returned collections. This can be replaced providing any
    | custom implementation implements the contract:
    | \Jamesyps\Myriad\Contracts\ComponentRepositoryInterface
    |
    */

    'repository' => \Jamesyps\Myriad\Repositories\ComponentRepository::class,

];
