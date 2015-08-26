<?php
include 'vendor/autoload.php';

use JonnyW\PhantomJs\Client;
use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;

$serviceContainer = ServiceContainer::getInstance();
$procedureLoader = $serviceContainer->get('procedure_loader_factory')->createProcedureLoader(dirname(__FILE__));

$client = Client::getInstance();
$client->getProcedureLoader()->addLoader($procedureLoader);

$req = $client->getMessageFactory()->createRequest();
$res = $client->getMessageFactory()->createResponse();

$req->setType('procedure');
$fragment = 'products';
$req->setUrl('http://angular.demo/'.$fragment);

$client->send($req, $res);

ob_start();
echo $res->getContent();
file_put_contents('snapshot/'.$fragment,ob_get_contents());
ob_end_flush();