<?php

namespace Pleasepay\Provider;

use Silex\Application;
use Silex\Provider\SwiftmailerServiceProvider;

class PpSwiftmailerServiceProvider extends SwiftmailerServiceProvider
{
    public function register(Application $app)
    {
        parent::register($app);

        // Gmail MTA configuration
        /*
        $app['swiftmailer.options'] = array(
            'host' => 'smtp.gmail.com',
            'port' => 465,
            'username' => 'iagayev@gmail.com',
            'password' => "******************",
            'encryption' => 'ssl',
            'auth_mode' => 'login'
        );
        */

        // Pleasepay MTA configuration
        $app['swiftmailer.options'] = array(
            'host' => 'localhost',
            'port' => 25,
            'username' => 'imran@pleasepay.co.uk',
            'password' => "Pir4l11&",
        );

        // important to turn off the spool
        $app['swiftmailer.use_spool'] = false;

        // Raw Implementation of SwiftMailer email sending
        // Create the Transport
        /*
        $transport = \Swift_SmtpTransport::newInstance('smtp.gmail.com',465)
            ->setUsername('iagayev@gmail.com')
            ->setPassword('Royalcongress489115@')
            ->setEncryption('ssl')
            ->setAuthMode('login');

        $mailer = \Swift_Mailer::newInstance($transport);

        $message = \Swift_Message::newInstance()
            ->setSubject('Welcome')
            ->setFrom(array('pleasepay@pleasepay.co.uk'))
            ->setTo(array('imran.aghayev@yahoo.co.uk'))
            ->setBody('hello world')
            ->setContentType("text/plain");

        $mailer->send($message);
        */
    }
}