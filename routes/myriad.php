<?php

/*
|--------------------------------------------------------------------------
| Root
|--------------------------------------------------------------------------
|
| The root path is where the components are displayed. This can be
| modified in config/myriad.php.
|
*/

Route::get('/')->uses('ComponentsController')->name('root');

/*
|--------------------------------------------------------------------------
| Preview
|--------------------------------------------------------------------------
|
| The preview route is used to render a standalone version of each
| component. This is typically displayed in an iframe to prevent
| any package styles from polluting the component appearance.
|
*/

Route::get('/preview')->uses('PreviewController')->name('preview');
