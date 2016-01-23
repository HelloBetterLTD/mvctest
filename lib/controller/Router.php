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

	/**
	 *
	 */
	public static function route()
	{
		$url = $_GET['url'];
		$parts = explode('/', $url);
		array_shift($parts);

		$response = new View();
		$action = "index";

		if(count($parts) == 1 && $parts[0] == ""){
			$parts[0] = 'home';
		}
		if(count($parts) > 1 && $parts[1] != ""){
			$action = trim($parts[1]);
		}

		$processed = false;

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

			if(method_exists($controller, $action)){
				$response->setContents($controller->$action());
				$processed = true;
			}


		}
		else if ($page = Page::find_one("URLSegment = '" . DB::raw2sql($parts[0]) . "'")){
			$controller = $page->getController();
			$response->setController($controller);
			if(method_exists($controller, $action)){
				$response->setContents($controller->$action());
				$processed = true;
			}
		}


		if(!$processed) {
			$controller = new NotFoundController();
			$page = $controller->getDefaultRecord();
			$controller->setRecord($page);
			$response->setController($controller);
			$response->setContents($controller->index());
		}

		if(!headers_sent()){
			http_response_code($controller->getHTTPCode());
		}

		echo $response->render();

	}

} 