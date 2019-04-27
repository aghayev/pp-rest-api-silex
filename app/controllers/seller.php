<?php

// New Invoice page, Seller lookup for guest users
use Symfony\Component\HttpFoundation\Request;

// helper for looking up sellers by anonymous users (i.e. no token provided by user)
function lookupSellerForGuestUser($email, $app) {
    $sql = 'SELECT seller.email FROM seller ';
    $sql .= 'WHERE seller.email LIKE ? ';
    $sellers = $app['db']->fetchAll($sql,array("{$email}%"));

    if (is_array($sellers) && !empty($sellers)) {
        $response['code'] = 200;
        $response['message'] = 'Success';
        $response['sellers'] = $sellers;
    }
    else {
        $response['code'] = 603;
        $response['message'] = 'Seller not found';
    }

    return $app->json($response);
}

// New Invoice page, Seller lookup for authenticated and guest users
$app->get('/1.0/seller/{email}', function (Request $request, $email) use ($app) {

    // get user security token from headers
    $token = $request->headers->get($app['token_header_name']);

    // for guest user
    if (!$token) {
        return lookupSellerForGuestUser($email, $app);
    }

    // for authenticated(?) user

    /*$sql = 'SELECT IF(DATE_ADD(NOW(), INTERVAL '.$app['mysql_token_timeout'].' DAY)>session.created,1,0) AS token_expired,
    seller.id,seller.name,seller.logo_name,seller.email,seller.address_line_1,seller.address_line_2,seller.address_line_3,seller.post_code,seller.telephone,seller.vat FROM seller ';
    $sql .= 'INNER JOIN session ON session.seller_id = seller.id ';
    $sql .= 'WHERE session.token = ? ';
    $sql .= 'AND seller.email LIKE ? ';*/

    $sql =
/** @lang MySQL */
<<<SQL
SELECT
  IF(DATE_ADD(NOW(), INTERVAL {$app['mysql_token_timeout']} DAY) > session.created, 1, 0) AS token_expired,
  seller.id,
  seller.name,
  seller.logo_name,
  seller.email,
  seller.address_line_1,
  seller.address_line_2,
  seller.address_line_3,
  seller.post_code,
  seller.telephone,
  seller.vat
FROM seller
  INNER JOIN session ON session.seller_id = seller.id
WHERE session.token = ? AND seller.email LIKE ?
SQL;

    $sellers = $app['db']->fetchAll($sql,array($token,"{$email}%"));

    $tokenExpired = false;
    if (is_array($sellers) && !empty($sellers)) {
        for ($i=0;$i<count($sellers);$i++) {
            if ($sellers[$i]['token_expired']>0) {
                $tokenExpired = true;
            }
            unset($sellers[$i]['token_expired']);
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
            $response['sellers'] = $sellers;
        }
    }
    else {
        $response['code'] = 603;
        $response['message'] = 'Seller not found';
    }

    return $app->json($response);
});

// Seller Settings
$app->get('/1.0/seller/{token}/{id}', function ($id) use ($app) {
    $sql = "SELECT id,name,logo_name,email,address_line_1,address_line_2,address_line_3,post_code,telephone,vat FROM seller WHERE id = ?";
    $seller = $app['db']->fetchAssoc($sql, array((int) $id));

    return $app->json($seller);
})->assert('id', '\d+');

$app->post('/1.0/seller', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {

    if ($request->request->get('email')===null) {
        $response['code'] = 600;
        $response['message'] = 'Error occured. Email missing';

        return $app->json($response, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
    }

    $email = $request->request->get('email');

    // Validation if Invoice belongs to Seller
    $sql = "SELECT COUNT(*) found FROM seller WHERE email = ?";
    $seller = $app['db']->fetchAssoc($sql, array($email));

    if ($seller['found']==1) {
        $response['code'] = 605;
        $response['message'] = 'Error occured. Seller already exists';
    }
    else {
        $post = array(
            'email'  => $email,
            'name'  => $request->request->get('name')
        );

        if ($request->request->get('logo_name')!==null) {
            $post['logo_name'] = $request->request->get('logo_name');
        }

        if ($request->request->get('address_line_1')!==null) {
            $post['address_line_1']  = $request->request->get('address_line_1');

            if ($request->request->get('address_line_2')!==null) {
                $post['address_line_2']  = $request->request->get('address_line_2');
            }
            if ($request->request->get('address_line_3')!==null) {
                $post['address_line_3']  = $request->request->get('address_line_3');
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

        if ($request->request->get('password')!==null) {
            $post['password'] = md5($request->request->get('password'));
        }

        $result = $app['db']->insert('seller', $post);

        if ($result ==1) {
            $response['code'] = 200;
            $response['message'] = 'Success';
            $response['seller_id'] = $app['db']->lastInsertId();
        }
        else {
            $response['code'] = 607;
            $response['message'] = 'Error occured. Seller create failed';
        }
    }



    return $app->json($response, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
});

$app->post('/1.0/update/seller', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {


    if ($request->request->get('token')===null) {
        $response['code'] = 600;
        $response['message'] = 'Error occured. Token missing';

        return $app->json($response, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
    }

    if ($request->request->get('seller_id')===null) {
        $response['code'] = 600;
        $response['message'] = 'Error occured. Seller missing';

        return $app->json($response, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
    }

    $token = $request->request->get('token');
    $sellerId = $request->request->get('seller_id');

    // Validation if Invoice belongs to Seller
    $sql = "SELECT COUNT(*) found FROM seller ";
    $sql .= 'INNER JOIN session ON session.seller_id = seller.id ';
    $sql .= 'WHERE session.token = ? ';
    $sql .= 'AND seller.id = ? ';

    $seller = $app['db']->fetchAssoc($sql, array($token, $sellerId));

    if ($seller['found']==0) {
        $response['code'] = 603;
        $response['message'] = 'Seller not found';
    }
    else {
        $post = array();

        if ($request->request->get('name')!==null) {
            $post['name'] = $request->request->get('name');
        }

        if ($request->request->get('logo_name')!==null) {
            $post['logo_name'] = $request->request->get('logo_name');
        }

        if ($request->request->get('address_line_1')!==null) {
            $post['address_line_1']  = $request->request->get('address_line_1');

            if ($request->request->get('address_line_2')!==null) {
                $post['address_line_2']  = $request->request->get('address_line_2');
            }
            if ($request->request->get('address_line_3')!==null) {
                $post['address_line_3']  = $request->request->get('address_line_3');
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

        if ($request->request->get('password')!==null) {
            $post['password'] = md5($request->request->get('password'));
        }

        $result = $app['db']->update('seller', $post, array('id' => $sellerId));

        if ($result ==1) {
            $response['code'] = 200;
            $response['message'] = 'Success';
            $response['seller_id'] = $sellerId;
        }
        else {
            $response['code'] = 620;
            $response['message'] = 'Error occured. Seller update failed';
        }
    }

    return $app->json($response, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
});
