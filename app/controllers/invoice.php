<?php

$app->get('/1.0/invoice', function () use ($app) {
    $sql = "SELECT * FROM invoice";
    $invoices = $app['db']->fetchAll($sql);

    return $app->json($invoices);
});

$app->get('/1.0/invoice/{id}', function ($id) use ($app) {
    $sql = "SELECT id, name FROM invoice WHERE id = ?";
    $invoice = $app['db']->fetchAssoc($sql, array((int) $id));

    return $app->json($invoice);
})->assert('id', '\d+');

$app->post('/1.0/invoice', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {
    $post = array(
        'number' => $request->request->get('number'),
        'buyer_id'  => $request->request->get('buyer_id'),
        'seller_id'  => $request->request->get('seller_id'),
        'status' => 1
    );

    $app['db']->insert('invoice', $post);
    $post['invoice_id'] = $app['db']->lastInsertId();

    return $app->json($post, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
});