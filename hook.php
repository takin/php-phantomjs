<?php
require_once 'vendor/autoload.php';

use JonnyW\PhantomJs\Client;
use JonnyW\PhantomJs\DependencyInjection\ServiceContainer;
/**
* hook
*/
class Prerender
{
	private $client;
	private $request;
	private $response;
	private $url = 'http://angular.demo/';

	function __construct($procedureLocation, $procedureName)
	{
		$serviceContainer = ServiceContainer::getInstance();
		$procedureLoader = $serviceContainer->get('procedure_loader_factory')->createProcedureLoader($procedureLocation);

		$this->client = Client::getInstance();
		$this->client->getProcedureLoader()->addLoader($procedureLoader);
		$this->request = $this->client->getMessageFactory()->createRequest();
		$this->response = $this->client->getMessageFactory()->createResponse();		
		$this->request->setType($procedureName);
	}

	private function run()
	{
		$this->client->send($this->request, $this->response);
	}

	public function get($fragment)
	{
		$this->request->setUrl($this->url.$fragment);
		$this->run();
		return $this->response;
	}

	public function save($object, $path)
	{
		ob_start();
		echo $object->getContent();
		file_put_contents($path, ob_get_contents());
		ob_end_flush();
	}
}

$fragment = 'products';
$prerender = new Prerender('procedures','angular');
$prerender->save($prerender->get($fragment), '../snapshots/'.$fragment.'.html');