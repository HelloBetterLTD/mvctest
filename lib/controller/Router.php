<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 4:31 PM
 * To change this template use File | Settings | File Templates.
 */

class Router extends Object
{

	public static function route()
	{
		$url = $_GET['url'];
		$parts = explode('/', $url);
		array_shift($parts);

		$response = new View();

		if($parts[0] && ClassManifest::has_class($parts[0]) && ClassManifest::is_a($parts[0], 'Controller')){

			$page = new Page();
			$page->ID = -1;
			$page->URLSegment = $parts[0];
			$page->Title = 'Default Controller';

			$controller = new $parts[0]();

			if(method_exists($controller, 'getDefaultRecord')){
				$page = $controller->getDefaultRecord();
			}

			$controller->setRecord($page);
			$response->setController($controller);
			$response->setContents($controller->index());

		}

		echo $response->render();

	}

} 