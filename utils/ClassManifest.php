<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 2:11 PM
 * To change this template use File | Settings | File Templates.
 */

class ClassManifest extends Manifest
{

	private static $class_manifest = null;

	/**
	 * @return bool|void
	 */
	public static function make_manifest()
	{

		$files = self::get_file_manifest();
		$parser = self::get_namespaced_class_parser();
		$classes = array();

		foreach($files as $path => $info){
			if($info['extension'] == 'php' || $info['extension'] == 'php5'){
				$tokens = token_get_all(file_get_contents($path));
				$classTokens = $parser->findAll($tokens);
				if(!empty($classTokens)){
					foreach($classTokens as $classToken){
						$classes[$classToken['className']] = array(
							'path'		=> $path,
							'extends'	=> isset($classToken['extends']) ? $classToken['extends'] : array()
						);
					}
				}
			}
		}

		self::$class_manifest = $classes;

		foreach($classes as $class => $classInfo){
			$children = array();
			foreach($classes as $otherClass => $otherClassInfo){
				if(self::is_a($otherClass, $class)){
					$children[] = $otherClass;
				}
			}
			$classes[$class]['children'] = $children;
		}
		self::$class_manifest = $classes;

		file_put_contents(TEMP_PATH . DIRECTORY_SEPARATOR . 'class_manifest', serialize($classes));
		self::$class_manifest = $classes;
	}


	/**
	 * @param $class
	 * @return bool
	 */
	public static function has_class($class)
	{
		return array_key_exists($class, self::get_class_manifest());
	}


	/**
	 * @param $class
	 * @param $type
	 * @return bool
	 */
	public static function is_a($class, $type)
	{
		if(self::has_class($class)){
			if($class == $type){
				return true;
			}
			else if(isset(self::$class_manifest[$class]['extends'])){
				if(in_array($type, self::$class_manifest[$class]['extends'])){
					return true;
				}
				if(!empty(self::$class_manifest[$class]['extends'])){
					return self::is_a(self::$class_manifest[$class]['extends'][0], $type);
				}
			}
		}
		return false;
	}


	/**
	 * @param $class
	 * @return mixed
	 */
	public static function subclasses_for($class)
	{
		if(self::has_class($class)){
			return self::$class_manifest[$class]['children'];
		}
	}


	public static function get_ancestry($class)
	{
		$ancestry = array();
		$manifest = self::get_class_manifest();
		if(isset($manifest[$class]) && isset($manifest[$class]['extends']) && !empty($manifest[$class]['extends'])){
			$ancestry[] = $manifest[$class]['extends'][0];
			$ancestry = array_merge($ancestry, self::get_ancestry($manifest[$class]['extends'][0]));
		}
		return $ancestry;
	}


	/**
	 * @return mixed|null
	 */
	public static function get_class_manifest()
	{
		if(!self::$class_manifest){
			self::$class_manifest = unserialize(file_get_contents(TEMP_PATH . '/class_manifest'));
		}
		return self::$class_manifest;
	}


	/**
	 * @return TokenisedRegularExpression
	 */
	public static function get_namespaced_class_parser() {
		return new TokenisedRegularExpression(array(
			0 => T_CLASS,
			1 => T_WHITESPACE,
			2 => array(T_STRING, 'can_jump_to' => array(8, 16), 'save_to' => 'className'),
			3 => T_WHITESPACE,
			4 => T_EXTENDS,
			5 => T_WHITESPACE,
			6 => array(T_NS_SEPARATOR, 'save_to' => 'extends[]', 'optional' => true),
			7 => array(T_STRING, 'save_to' => 'extends[]', 'can_jump_to' => array(6, 16)),
			8 => T_WHITESPACE,
			9 => T_IMPLEMENTS,
			10 => T_WHITESPACE,
			11 => array(T_NS_SEPARATOR, 'save_to' => 'interfaces[]', 'optional' => true),
			12 => array(T_STRING, 'can_jump_to' => array(11, 16), 'save_to' => 'interfaces[]'),
			13 => array(T_WHITESPACE, 'optional' => true),
			14 => array(',', 'can_jump_to' => 11, 'save_to' => 'interfaces[]'),
			15 => array(T_WHITESPACE, 'can_jump_to' => 11),
			16 => array(T_WHITESPACE, 'optional' => true),
			17 => '{',
		));
	}

} 