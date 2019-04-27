<?php

$app->get('/1.0/seller/welcome/{to}', function ($to) use ($app) {

    $emailTemplate = $app['twig']->render('emails/seller_welcome.twig', array(
        'pleasepay_url' => $app['base_url'],
        'seller_name' => 'Dezrez Legal Ltd.',
        'email' => $to,
        'password' => Pleasepay\TokenHelper::generateRandomString(7),
        'link' => $app['base_url'].Pleasepay\TokenHelper::generateToken(10)
    ));

    $message = \Swift_Message::newInstance()
        ->setSubject('Welcome to pleasepay.co.uk')
        ->setFrom(array('pleasepay@pleasepay.co.uk'))
        ->setTo(array($to))
        ->setBody($emailTemplate)
        // ->setContentType("text/plain");
        ->setContentType("text/html");

    $app['mailer']->send($message);

    $response['code'] = 200;
    $response['message'] = 'Success';

    return $app->json($response, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
});

$app->get('/1.0/customer/welcome/{to}', function ($to) use ($app) {

    $emailTemplate = $app['twig']->render('emails/customer_welcome.twig', array(
        'pleasepay_url' => $app['base_url'],
        'customer_name' => 'Feodor Kouznetsov',
        'seller_name' => 'Dezrez Legal Ltd.',
        'email' => $to,
        'password' => Pleasepay\TokenHelper::generateRandomString(7),
        'link' => $app['base_url'].'/password/'.Pleasepay\TokenHelper::generateToken(10)
    ));

    $message = \Swift_Message::newInstance()
        ->setSubject('Welcome to pleasepay.co.uk')
        ->setFrom(array('pleasepay@pleasepay.co.uk'))
        ->setTo(array($to))
        ->setBody($emailTemplate)
        // ->setContentType("text/plain");
        ->setContentType("text/html");

    $invoiceName = Pleasepay\TokenHelper::generateToken(7);
    $tmpInvoice = __DIR__.'/../../web/invoices/'.$invoiceName.'.html';
    $finalInvoice = __DIR__.'/../../web/invoices/'.$invoiceName.'.pdf';
    if (@file_exists($tmpInvoice)) {
        @unlink($tmpInvoice);
    }
    if (@file_exists($finalInvoice)) {
        @unlink($finalInvoice);
    }

    file_put_contents($tmpInvoice ,$emailTemplate);

    exec($app['wkhtmltopdf'] . ' ' . $tmpInvoice . ' ' . $finalInvoice. ' > /dev/null & ');
    sleep(3);

    $message->attach(
        \Swift_Attachment::fromPath($finalInvoice)->setFilename(basename($finalInvoice))
    );

    $app['mailer']->send($message);

    $response['code'] = 200;
    $response['message'] = 'Success';

    return $app->json($response, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
});