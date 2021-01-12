<?php

Route::group(['middleware' => ['web', 'auth', 'akk-back', 'permission:media'], 'namespace' => 'Akkurate\LaravelMedia\Http\Controllers\Back', 'prefix' => 'brain/{uuid}/media', 'as' => 'brain.media.'], function () {
    Route::resource('medias', 'MediaController')->only('index');
});
