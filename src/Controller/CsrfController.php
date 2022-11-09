<?php

namespace App\Controller;

use DI\Container;
use Slim\Csrf\Guard;
use Slim\Factory\AppFactory;

require __DIR__ . '/vendor/autoload.php';

class CsrfController extends MainController {

    public function defaultMethod(){
        // Start PHP session
        session_start();

        // Create Container
        $container = new Container();
        AppFactory::setContainer($container);

        // Create App
        $app = AppFactory::create();
        $responseFactory = $app->getResponseFactory();

        // Register Middleware On Container
        $container->set('csrf', function () use ($responseFactory) {
            return new Guard($responseFactory);
        });

        $app->get('/api/route',function ($request, $response, $args) {
            $csrf = $this->get('csrf');
            $nameKey = $csrf->getTokenNameKey();
            $valueKey = $csrf->getTokenValueKey();
            $name = $request->getAttribute($nameKey);
            $value = $request->getAttribute($valueKey);

            $tokenArray = [
                $nameKey => $name,
                $valueKey => $value
            ];
    
            return $response->write(json_encode($tokenArray));
        })->add('csrf');

        $app->post('/api/myEndPoint',function ($request, $response, $args) {
            //Do my Things Securely!
        })->add('csrf');

        $app->run();
    }
}