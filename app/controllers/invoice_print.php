<?php


$app->get('/1.0/invoice_print', function ($id) use ($app) {

    // sample invoice data
    $sampleInvoice = array(
        'invoice' => array(
            'number' => 123456789012,
            'currency' => 'zl',
            'created' => '24.10.2016',
            'due_date' => '24.11.2016',
        ),
        'summary' => array(
            'prepayment_value' => 5,
            'subtotal' => 162456.54,
            'vat_value' => 5,
            'total' => 199701.28,
            'to_be_prepaid' => 199701.28,
        ),
        'seller' => array(
            'name' => 'PleasePay Ltd',
            'person_name' => 'Feodor Kouznetsov',
            'address_line_1' => 'Flat 2, 6 Montrose Place',
            'address_line_2' => '', // optional
            'address_line_3' => '', // optional
            'post_code' => 'SW1X 7DU',
            'city' => 'London',
            'country' => 'UK',
            'vat_label' => 'VAT',
            'vat_id' => 'GB 278 78722',
            'company_id' => '07776689',
        ),
        'buyer' => array(
            'name' => 'Please Receive Ltd',
            'person_name' => 'Vasiliy Vetrov',
            'address_line_1' => 'Veternya Ulica 16, korpus 2, kv 6',
            'address_line_2' => '', // optional
            'address_line_3' => '', // optional
            'post_code' => '192 638',
            'city' => 'Veternyj gorodok',
            'country' => 'Russia',
            'vat_label' => 'NDS',
            'vat_id' => 'RU78 78373 7837',
            'company_id' => '88732987 28397 982',
        ),
        'products' => [
            array(
                'id' => 1,
                'description' => 'prod 1',
                'quantity' => 2,
                'price' => 10.00,
                'final_price' => 20.00,
            ),
            array(
                'id' => 2,
                'description' => 'prod 2',
                'quantity' => 1,
                'price' => 15.00,
                'final_price' => 15.00,
            ),
            array(
                'id' => 3,
                'description' => 'prod 3',
                'quantity' => 1,
                'price' => 30.00,
                'final_price' => 30.00,
            ),
        ],
        'banking_details' => array(
            'iban' => '1234321234',
            'swift' => '12343212341',
            'account' => 'your_account',
            'sort_code' => array(20, 20, 20),
            'note_to_recipient' => '', // optional
        ),
    );

    $template = $app['twig']->render('invoice_print.twig', $sampleInvoice);


    $tmpInvoice = __DIR__.'/../../web/invoices/teatr.html';
    $finalInvoice = __DIR__.'/../../web/invoices/teatr.pdf';
    file_put_contents($tmpInvoice ,$template);

    exec($app['wkhtmltopdf'] . ' ' . $tmpInvoice . ' ' . $finalInvoice. ' > /dev/null & ');

    return $template;
});