<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 2:12 PM
 * To change this template use File | Settings | File Templates.
 */

abstract class Object
{

	private static $singletons = array();
	
	public static function find_or_make_singleton($class)
	{
		if(!isset(self::$singletons[$class])) {
			self::$singletons[$class] = new $class();
		}
		return self::$singletons[$class];
	}
	
	public function methodExists($method)
	{
		return method_exists($this, $method);
	}
	
	
	

} 

function singleton($class) 
{
	return Object::find_or_make_singleton($class);
}