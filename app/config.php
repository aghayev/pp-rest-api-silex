<?php

// Please set to false in a production environment
$app['debug'] = true;

$app['base_url'] = 'https://pp-api.aghayev.com/';
$app['mysql_token_timeout'] = '-1';

// pp-api/console based local version 0.9.9 statically linked - no Bold font not generated
// $app['wkhtmltopdf'] = __DIR__.'/../console/wkhtmltopdf-amd64';

// system level installed version 0.9.9 - Bold font generated correct
$app['wkhtmltopdf'] = '/usr/local/bin/wkhtmltopdf';

$app['token_header_name'] = 'Client-Security-Token';
