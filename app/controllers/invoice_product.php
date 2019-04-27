<?php

// Adding product and linking to invoice
$app->post('/1.0/invoice_product', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {

    $invoiceId = $request->request->get('invoice_id');
    $sellerId = $request->request->get('seller_id');
    $number = $request->request->get('number');

    // Validation if Invoice belongs to Seller
    $sql = "SELECT seller_id FROM invoice WHERE id = ?";
    $invoice = $app['db']->fetchAssoc($sql, array((int) $invoiceId));

    if ($invoice['seller_id']==$sellerId) {

        // Try to find the product by number and seller_id
        $sql = "SELECT id FROM product WHERE seller_id = ? AND number = ? LIMIT 0,1";
        $product = $app['db']->fetchAssoc($sql, array((int) $sellerId, $number));

        $productId = null;
        // it is a known product
        if ($product['id']>0) {
            $productId = $product['id'];

            $result['code'] = 200;
            $result['message'] = 'Success';
            $result['product_id'] = $productId;
        }
        // it is a new product
        else {
            $productPost = array(
                'number' => $number,
                'seller_id' => $sellerId,
                'description' => $request->request->get('description'),
                'price' => $request->request->get('price'),
            );

            $app['db']->insert('product', $productPost);
            $productId = $app['db']->lastInsertId();
        }

        // Linking product to invoice
        if ($productId > 0) {
            $post = array(
                'invoice_id' => $invoiceId,
                'product_id'  => $productId,
                'quantity'  => $request->request->get('quantity'),
                'price'  => $request->request->get('price'),
            );

            $app['db']->insert('invoice_product', $post);

            $result['code'] = 200;
            $result['message'] = 'Success';
        }
        else {
            $result['code'] = 612;
            $result['message'] = 'Invoice Product not saved';
        }

        $result['code'] = 200;
        $result['message'] = 'Success';
        $result['product_id'] = $productId;
    }
    else {
        $result['code'] = 610;
        $result['message'] = 'Invoice Seller not found';
    }

    return $app->json($result, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
});