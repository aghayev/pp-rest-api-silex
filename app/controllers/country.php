<?php

$app->get('/1.0/country', function () use ($app) {
    $sql = "SELECT * FROM country";
    $countries = $app['db']->fetchAll($sql);

    return $app->json($countries);
});
