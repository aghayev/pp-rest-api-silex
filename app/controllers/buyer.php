<?php
// Buyer details
use Symfony\Component\HttpFoundation\Request;

$app->get('/1.0/buyer/{id}', function ($id) use ($app) {
    $sql = "SELECT id,name,email,address_line_1,address_line_2,address_line_3,post_code,telephone,vat,delivery_name,delivery_address_line_1,delivery_address_line_2,delivery_address_line_3,delivery_post_code
     FROM buyer WHERE id = ?";
    $buyer = $app['db']->fetchAssoc($sql, array((int) $id));

    return $app->json($buyer);
})->assert('id', '\d+');


// Buyers list page, buyers lookup
$app->get('/1.0/buyer', function (Request $request) use ($app) {

    $token = $request->headers->get($app['token_header_name']);

    $sql = 'SELECT IF(DATE_ADD(NOW(), INTERVAL '.$app['mysql_token_timeout'].' DAY)>session.created,1,0) AS token_expired,
    buyer.id,buyer.name,buyer.email,buyer.address_line_1,buyer.address_line_2,buyer.address_line_3,buyer.post_code,buyer.telephone,buyer.vat,buyer.delivery_name,buyer.delivery_address_line_1,buyer.delivery_address_line_2,buyer.delivery_address_line_3,buyer.delivery_post_code FROM buyer ';
    $sql .= 'INNER JOIN session ON session.seller_id = buyer.seller_id ';
    $sql .= 'WHERE session.token = ? ';

    $buyers = $app['db']->fetchAll($sql,array($token));

    $tokenExpired = false;
    if (is_array($buyers)) {
        for ($i=0;$i<count($buyers);$i++) {
            if ($buyers[$i]['token_expired']>0) {
                $tokenExpired = true;
            }
            unset($buyers[$i]['token_expired']);
        }
    }

    if ($tokenExpired == true) {

        //Delete expired token
        $app['db']->delete('session',array('token' => $token));

        $response['code'] = 650;
        $response['message'] = 'Token has expired';
    }
    else {
        $response['code'] = 200;
        $response['message'] = 'Success';
        $response['buyers'] = $buyers;
    }

    return $app->json($response);
});

$app->post('/1.0/buyer', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {

    $email = $request->request->get('email');

    // Validation if Invoice belongs to Seller
    $sql = "SELECT COUNT(*) found FROM buyer WHERE email = ?";
    $buyer = $app['db']->fetchAssoc($sql, array($email));

    if ($buyer['found']==1) {
        $response['code'] = 604;
        $response['message'] = 'Customer already exists';
    }
    else {
    $post = array(
        'email'  => $email,
        'name'  => $request->request->get('name')
    );

    if ($request->request->get('account_number')!==null) {
        $post['account_number'] = $request->request->get('account_number');
    }

        if ($request->request->get('address_line_1')!==null) {
            $post['address_line_1']  = $request->request->get('address_line_1');

            if ($request->request->get('address_line_2')!==null) {
                $post['address_line_2']  = $request->request->get('address_line_2');
            }
            if ($request->request->get('address_line_3')!==null) {
                $post['address_line_3']  = $request->request->get('address_line_3');
            }

            if ($request->request->get('delivery_name')!==null) {
                $post['delivery_name']  = $request->request->get('delivery_name');
            }
            if ($request->request->get('delivery_address_line_1')!==null) {
                $post['delivery_address_line_1']  = $request->request->get('delivery_address_line_1');
            }
            if ($request->request->get('delivery_address_line_2')!==null) {
                $post['delivery_address_line_2']  = $request->request->get('delivery_address_line_2');
            }
            if ($request->request->get('delivery_address_line_3')!==null) {
                $post['delivery_address_line_3']  = $request->request->get('delivery_address_line_3');
            }
        }

        if ($request->request->get('post_code')!==null) {
            $post['post_code']  = $request->request->get('post_code');
        }

        if ($request->request->get('telephone')!==null) {
            $post['telephone']  = $request->request->get('telephone');
        }

        if ($request->request->get('vat')!==null) {
            $post['vat']  = $request->request->get('vat');
        }

        $result = $app['db']->insert('buyer', $post);

        if ($result ==1) {
            $response['code'] = 200;
            $response['message'] = 'Success';
            $response['buyer_id'] = $app['db']->lastInsertId();
        }
        else {
            $response['code'] = 609;
            $response['message'] = 'Customer create failed';
        }
    }

    return $app->json($response, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
});