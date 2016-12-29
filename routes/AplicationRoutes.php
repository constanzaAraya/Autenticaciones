<?php
$app->group(['prefix' => 'apl'], function () use ($app) {
    //$app->get('/{cod}', 'AplicationController@get');
    $app->get('list', 'AplicationController@listApl');
});