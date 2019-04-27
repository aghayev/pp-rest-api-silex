<?php

$app->post('/1.0/logo', function (Symfony\Component\HttpFoundation\Request $request) use ($app) {

    $app['translator.domains'] = array(
        'messages' => array(
            'en' => array(
                '200'   => 'Success',
                '601'   => 'Image not saved',
                '602'   => 'Image not found or wrong type',
                '603'   => 'Seller not found',
            ),
            'fr' => array(
                '200'     => 'Success',
                '601'   => 'Image not saved',
                '602'   => 'Image not found or wrong type',
                '603'   => 'Seller not found',
            ),
        ),
    );

    // validate Seller
    $seller_id = $request->request->get('seller_id');

    $sql = "SELECT COUNT(*) AS found FROM seller WHERE id = ?";
    $seller = $app['db']->fetchAssoc($sql, array((int) $seller_id));

    if ($seller['found']>0) {

        $file = $request->files->get('file');
        $mimeType = $file->getMimeType();
        $size = $file->getSize();

        if ($file !== null && in_array($mimeType, array('image/png','image/jpg','image/jpeg','image/gif'))) {

                $pathInfo = pathinfo($file->getClientOriginalName());
                $newFilename = Pleasepay\TokenHelper::generateToken(5).'.'.$pathInfo['extension'];

                $post = array(
                'seller_id' => $seller_id,
                'name' => $pathInfo['filename'],
                'filename' =>  $newFilename,
                'size' => $size,
                'mime' => $mimeType,
                'status' => 1,
                );

                // Image lookup
                $sql = "SELECT id, filename FROM image WHERE seller_id = ?";
                $image = $app['db']->fetchAssoc($sql, array((int) $seller_id));

                if ($image['id']!==null) {
                    $post['updated'] = date('Y-m-d H:i:s');
                    $imageId = $image['id'];
                    $oldFilename = $image['filename'];
                    $app['db']->update('image', $post, array('id' => $imageId));
                }
                else {
                    $post['created'] = date('Y-m-d H:i:s');
                    $imageId = $app['db']->insert('image', $post);
                    $oldFilename = null;
                }

            if ($imageId>0) {

                if ($oldFilename !== null) {
                    unlink('../web/logo/'.$oldFilename);
                }

                $path = '../web/logo/';

                if ($file->move($path, $file->getClientOriginalName())) {
                    @rename($path.$file->getClientOriginalName(),$path.$newFilename);
                    $response['code'] = 200;
                    $response['message'] = $app['translator']->trans('200');
                    $response['image_id'] = $imageId;
                }
                else {
                    $response['code'] = 601;
                    $response['message'] = $app['translator']->trans('601');
                }

            }
            else {
                $response['code'] = 601;
                $response['message'] = $app['translator']->trans('601');
            }
        }
        else {
            $response['code'] = 602;
            $response['message'] = $app['translator']->trans('602');
        }
    }
    else {
        $response['code'] = 603;
        $response['message'] = $app['translator']->trans('603');
    }

    return $app->json($response, Symfony\Component\HttpFoundation\Response::HTTP_CREATED);
});