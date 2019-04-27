<?php

// New Invoice page, products lookup
use Symfony\Component\HttpFoundation\Request;

$app->get('/1.0/product/{name}', function (Request $request, $name) use ($app) {

    $token = $request->headers->get($app['token_header_name']);

    $sql = 'SELECT IF(DATE_ADD(NOW(), INTERVAL '.$app['mysql_token_timeout'].' DAY)>session.created,1,0) AS token_expired,
    product.number,product.seller_id,product.description,product.price FROM product ';
    $sql .= 'INNER JOIN session ON session.seller_id = product.seller_id ';
    $sql .= 'WHERE session.token = ? ';
    $sql .= 'AND description LIKE ? ';

    $products = $app['db']->fetchAll($sql, array($token,"{$name}%"));

    $tokenExpired = false;
    if (is_array($products)) {
        for ($i=0;$i<count($products);$i++) {
            if ($products[$i]['token_expired']>0) {
                $tokenExpired = true;
            }
            unset($products[$i]['token_expired']);
        }
    }

    if ($tokenExpired === true) {

        //Delete expired token
        $app['db']->delete('session',array('token' => $token));

        $response['code'] = 650;
        $response['message'] = 'Token has expired';
    }
    else {
        $response['code'] = 200;
        $response['message'] = 'Success';
        $response['products'] = $products;
    }

    return $app->json($response);
});