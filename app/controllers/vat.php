<?php

$app->get('/1.0/vat', function () use ($app) {
    $vat = array();

    for ($i=1;$i<100;$i++) {
        $vat[$i] = $i.'%';
    }

    return $app->json($vat);
});
