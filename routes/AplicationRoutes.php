<?php
$app->group(['prefix' => 'apl'], function () use ($app) {
    $app->get('/{cod}', 'AplicationController@get');
    $app->get('list', 'AplicationController@listApl');
    $app->post('', 'AplicationController@createApp');
    $app->put('/{id}', 'AplicationController@updateApp');
    $app->delete('/{id}', 'AplicationController@deleteApp');
});