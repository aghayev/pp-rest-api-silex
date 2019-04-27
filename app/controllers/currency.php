<?php

$app->get('/1.0/currency', function () use ($app) {
    $sql = "SELECT * FROM currency";
    $currencies = $app['db']->fetchAll($sql);

    return $app->json($currencies);
});
