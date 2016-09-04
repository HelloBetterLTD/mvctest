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

	private static $routes = array();

	public static function add_route($pattern, $class)
	{
		self::$routes[$pattern] = $class;
	}

	public static function has_route($pattern)
	{
		return isset(self::$routes[$pattern]);
	}

	public static function route_class($pattern)
	{
		return isset(self::$routes[$pattern]) ? self::$routes[$pattern] : null;
	}

	public static function get_base()
	{
		$base =  ConfigManifest::get_config('BasePath');
		if($base) {
			return $base;
		}

		$path = isset($_SERVER['SCRIPT_FILENAME']) ? $_SERVER['SCRIPT_FILENAME'] : '';
		$uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
		if($uri && $path) {

			if(substr($path, 0, strlen(BASE_PATH)) == BASE_PATH) {
				$folderToRemove = substr($path, strlen(BASE_PATH));
				if(substr($_SERVER['SCRIPT_NAME'], -strlen($folderToRemove)) == $folderToRemove) {
					$base = substr($_SERVER['SCRIPT_NAME'], 0, -strlen($folderToRemove));
					if(empty($base)){
						$base = '/';
					}
					return $base;
				}
			}
		}

		return '/';
	}

	/**
	 *
	 */
	public static function route()
	{
		$url = $_GET['url'];
		$base = Router::get_base();
		if($base && $base != '/' && strpos($url, $base) === 0){
			$url = substr($url, strlen($base));
		}
		$url = preg_replace('{/$}', '', $url);

		if(empty($url)){
			$url = '/';
		}

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

		Debug::log("URL Variables", $parts);

		if($parts[0] && Router::has_route($parts[0])){

			$page = new Page();
			$page->ID = -1;
			$page->URLSegment = $parts[0];
			$page->Title = 'Default Controller';

			$controllerClass = Router::route_class($parts[0]);

			$controller = new $controllerClass();

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
		else {
			$lastParentID = 0;
			$paramCounter = 0;
			foreach ($parts as $part){
				if ($page = Page::find_one("URLSegment = '" . DB::raw2sql($part) . "' AND ParentID = {$lastParentID}")){

					if ($paramCounter == count($parts) - 2) {
						$action = $parts[$paramCounter + 1];
						$controller = $page->getController();
						$response->setController($controller);
						if(method_exists($controller, $action)){
							$response->setContents($controller->$action());
							$processed = true;
						}
					}
					else if($paramCounter == count($parts) - 1) {
						$action = 'index';
						$controller = $page->getController();
						$response->setController($controller);
						if(method_exists($controller, $action)){
							$response->setContents($controller->$action());
							$processed = true;
						}
					}
					$lastParentID = (int)$page->ID;
					Debug::log('Page matched ' . $lastParentID . ' ' . $part);
				}
				$paramCounter += 1;

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