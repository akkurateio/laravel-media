<?php

Route::group(['middleware' => ['api', 'auth:api', 'akk-api', 'permission:media'], 'namespace' => 'Akkurate\LaravelMedia\Http\Controllers\Api', 'prefix' => 'api/v1/accounts/{uuid}/packages/media', 'as' => 'api.media.'], function() {
    Route::apiResource('types', 'TypeController');
    Route::apiResource('resources', 'ResourceController');
    Route::post('resources/{resource}/attach', 'ResourceController@attachMedia')->name('resources.attach');
    Route::post('resources/{resource}/detach', 'ResourceController@detachMedia')->name('resources.detach');
    Route::post('resources/{resource}/tags/attach', 'ResourceController@attachTag')->name('tags.attach');
    Route::post('resources/{resource}/tags/detach', 'ResourceController@detachTag')->name('tags.detach');
    Route::apiResource('medias', 'MediaController');
});