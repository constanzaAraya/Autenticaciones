<?php

$app->group(['prefix' => 'user'], function () use ($app) {
    $app->post('login', 'UserController@login');
    $app->post('', 'UserController@create');
    $app->get('/{id}', 'UserController@get');
    $app->put('/{id}', 'UserController@update');
    $app->delete('/{id}', 'UserController@delete');
    $app->patch('/{id}', 'UserController@pass');
    //$app->get('list', 'UserController@listUser');
});