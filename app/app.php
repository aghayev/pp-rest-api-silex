<?php

require_once __DIR__.'/bootstrap.php';

// init Silex app
//$app = new Silex\Application();
$app = new Pleasepay\Application();

// Configuration
require_once __DIR__.'/config.php';

$app->register(new Silex\Provider\MonologServiceProvider(), array());

//configure database connection
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver' => 'pdo_mysql',
        'host' => 'localhost',
        'dbname' => 'pp_db',
        'user' => 'pp-api',
        'password' => 'yG9s2h3!',
        'charset' => 'utf8',
    ),
));

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../templates',
));

$app->register(new Silex\Provider\UrlGeneratorServiceProvider());
$app['twig']->addFunction(new \Twig_SimpleFunction('path', function($url) use ($app) {
    return $app['url_generator']->generate($url);
}));

$app->register(new Pleasepay\Provider\PpSwiftmailerServiceProvider());

$app->register(new Silex\Provider\TranslationServiceProvider(), array(
    'locale_fallbacks'  => array('en')
));

/*
$app['translator.domains'] = array(
    'messages' => array(
        'en' => array(
            'hello'     => 'Hello %name%',
            'goodbye'   => 'Goodbye %name%',
        ),
        'de' => array(
            'hello'     => 'Hallo %name%',
            'goodbye'   => 'Tschüss %name%',
        ),
        'fr' => array(
            'hello'     => 'Bonjour %name%',
            'goodbye'   => 'Au revoir %name%',
        ),
    ),
    'validators' => array(
        'fr' => array(
            'This value should be a valid number.' => 'Cette valeur doit être un nombre.',
        ),
    ),
);

$app->get('/{_locale}/{message}/{name}', function ($message, $name) use ($app) {
    return $app['translator']->trans($message, array('%name%' => $name));
});
*/

$app->get('/admin/', function(Symfony\Component\HttpFoundation\Request $request) use ($app) {

    $token = $app['security.token_storage']->getToken();

    if (null !== $token) {
        $user = $token->getUser();
    }

    return $app['twig']->render('admin.twig', [
        'content' => ($app['security.authorization_checker']->isGranted('ROLE_ADMIN') ? 'You\'re logged in.' : 'You\'re not logged in.'),
    ]);
})->bind('admin');

$app->get('/login', function(Symfony\Component\HttpFoundation\Request $request) use ($app) {
    return $app['twig']->render(
        'login.twig',
        [
            'error' => $app['security.last_error']($request),
            'last_username' => $app['session']->get('_security.last_username')
        ]
    );
})->bind('login');

$app->get('/logout/', function(Symfony\Component\HttpFoundation\Request $request) use ($app) {
//    return  $this->get('security.context')->getToken();
    return "List of avaiable methods";
})->bind('logout');

$app->before(function (Symfony\Component\HttpFoundation\Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->after(function (Symfony\Component\HttpFoundation\Request $request,
                      Symfony\Component\HttpFoundation\Response $response) {
    $response->headers->set('Access-Control-Allow-Origin', '*');
});

// file upload

// default route
$app->get('/', function () {

    return "List of avaiable methods:
  - /email - sends email for testing purposes;\n
  - /seller - returns list of existing sellers;\n
  - /seller/{id} - returns seller details by id;\n
  - /buyer - returns list of existing buyers;\n
  - /buyer/{id} - returns buyer details by id;\n
  - /product - returns list of existing products;\n
  - /invoice_product/{id} - returns invoice products by id;\n
  - /invoice - returns list of existing invoices;\n
  - /invoice/{id} - returns invoice details by id;";
});

// if route not found, redirect home
$app->error(function (\Exception $e, $code) use ($app) {
    if (404 === $code) {
        return $app->redirect('/');
    }
});

require(__DIR__ . '/controllers/auth.php');
require(__DIR__ . '/controllers/dashboard.php');
require(__DIR__ . '/controllers/email.php');
require(__DIR__ . '/controllers/seller.php');
require(__DIR__ . '/controllers/buyer.php');
require(__DIR__ . '/controllers/invoice.php');
require(__DIR__ . '/controllers/invoice_product.php');
require(__DIR__ . '/controllers/invoice_print.php');
require(__DIR__ . '/controllers/product.php');

require(__DIR__ . '/controllers/vat.php');
require(__DIR__ . '/controllers/currency.php');
require(__DIR__ . '/controllers/country.php');
require(__DIR__ . '/controllers/logo.php');

return $app;