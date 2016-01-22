<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 4:03 PM
 * To change this template use File | Settings | File Templates.
 */

class ClassLoader
{

	private static $instance = null;

	public static function instance()
	{
		if(!self::$instance){
			self::$instance = new ClassLoader();
		}
		return self::$instance;
	}

	public function registerAutoLoader()
	{
		spl_autoload_register(array($this, 'loadClass'));
	}

	public function loadClass($class)
	{
		if ($path = $this->getItemPath($class)) {
			require_once $path;
		}
		return $path;
	}

	public function getItemPath($class){
		$manifest = ClassManifest::get_class_manifest();
		if(isset($manifest[$class])){
			return $manifest[$class]['path'];
		}
	}

} 