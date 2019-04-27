<?php

$app->post('/1.0/dashboard', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {

    $stats = array();
    $stats['summary'] = array();
    $stats['summary']['customers'] = array('count' => 123456);
    $stats['summary']['invoices']['created'] = array('count' => 3221, 'amount' => 11111111, 'currency' => 'EUR');
    $stats['summary']['invoices']['sent'] = array('count' => 3221, 'amount' => 11111111, 'currency' => 'EUR');

    $stats['summary']['invoices']['2016']['q1']['created'] = array('count' => 3221, 'amount' => 11111111, 'currency' => 'EUR');
    $stats['summary']['invoices']['2016']['q1']['sent'] = array('count' => 3221, 'amount' => 11111111, 'currency' => 'EUR');
    $stats['summary']['invoices']['2016']['q2']['created'] = array('count' => 3221, 'amount' => 11111111, 'currency' => 'EUR');
    $stats['summary']['invoices']['2016']['q2']['sent'] = array('count' => 3221, 'amount' => 11111111, 'currency' => 'EUR');
    $stats['summary']['invoices']['2016']['q3']['created'] = array('count' => 3221, 'amount' => 11111111, 'currency' => 'EUR');
    $stats['summary']['invoices']['2016']['q3']['sent'] = array('count' => 3221, 'amount' => 11111111, 'currency' => 'EUR');
    $stats['summary']['invoices']['2016']['q4']['created'] = array('count' => 3221, 'amount' => 11111111, 'currency' => 'EUR');
    $stats['summary']['invoices']['2016']['q4']['sent'] = array('count' => 3221, 'amount' => 11111111, 'currency' => 'EUR');

    $stats['logs'][0] = 'New invoice INV/2016/21 for Company Big Brother LTD Jul 28 at 5:58 PM';
    $stats['logs'][1] = 'New invoice INV/2016/21 for Company Big Brother LTD Jul 28 at 5:58 PM';
    $stats['logs'][2] = 'New invoice INV/2016/21 for Company Big Brother LTD Jul 28 at 5:58 PM';
    $stats['logs'][3] = 'New invoice INV/2016/21 for Company Big Brother LTD Jul 28 at 5:58 PM';
    $stats['logs'][4] = 'New invoice INV/2016/21 for Company Big Brother LTD Jul 28 at 5:58 PM';
    $stats['logs'][5] = 'New invoice INV/2016/21 for Company Big Brother LTD Jul 28 at 5:58 PM';
    $stats['logs'][6] = 'New invoice INV/2016/21 for Company Big Brother LTD Jul 28 at 5:58 PM';

    $result['code'] = 200;
    $result['message'] = 'Success';
    $result['stats'] = $stats;

    return $app->json($result, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
});
