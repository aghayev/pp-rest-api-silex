<?php

$app->post('/1.0/auth', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {

    $login = $request->request->get('login');
    $password = $request->request->get('password');

    $sql = "SELECT id FROM `seller` WHERE `email`= ? AND `password`= ? LIMIT 0,1";
    $res = $app['db']->fetchAssoc($sql, array($login, md5($password)));

    if ($res['id']>0) {

        $token = Pleasepay\TokenHelper::generateToken(12);

        $post = array(
            'seller_id' => $res['id'],
            'token' => $token,
            'created' => date("Y-m-d H:i:s", mktime())
        );

        $sql2 = "SELECT COUNT(*) AS ex FROM `session` WHERE `seller_id`= ?";
        $res2 = $app['db']->fetchAssoc($sql2, array($res['id']));

        if ($res2['ex']>0) {
            $app['db']->update('session', $post, array('seller_id' => $res['id']));
        }
        else {
            $app['db']->insert('session', $post);
        }

        $result['code'] = 200;
        $result['message'] = 'Success';
        $result['token'] = $token;
    }
    else {
        $result['code'] = 600;
        $result['message'] = 'Error occured';
    }

    return $app->json($result, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
});

$app->post('/1.0/auth/reset', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {

    $login = $request->request->get('login');

    $sql = "SELECT name FROM `seller` WHERE `email`= ? LIMIT 0,1";
    $res = $app['db']->fetchAssoc($sql, array($login));

    if (isset($res['name'])) {

        // Todo add db table to keep token

        $token = Pleasepay\TokenHelper::generateToken(10);

        $emailTemplate = $app['twig']->render('emails/password_reset.twig', array(
            'pleasepay_url' => 'https://www.pleasepay.co.uk',
            'seller_name' =>  $res[0]['name'],
            'email' => $login,
            'link' => 'http://www.pleasepay.co.uk/password/'.$token
        ));

        $message = \Swift_Message::newInstance()
            ->setSubject('Password Reset pleasepay.co.uk')
            ->setFrom(array('pleasepay@pleasepay.co.uk'))
            ->setTo(array($login))
            ->setBody($emailTemplate)
            ->setContentType("text/html");

        $app['mailer']->send($message);

        $result['code'] = 200;
        $result['message'] = 'Success';
    }
    else {
        $result['code'] = 601;
        $result['message'] = 'Seller not found';
    }

    return $app->json($result, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
});

$app->post('/1.0/auth/logout', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {

        // Todo add db table
        $token = $request->request->get('token');

        $result['code'] = 200;
        $result['message'] = 'Success';

    return $app->json($result, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
});