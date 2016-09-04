<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 2:14 PM
 * To change this template use File | Settings | File Templates.
 */


class ConfigManifest extends Manifest
{

	private static $config_manifest = null;

	/**
	 * @return bool|void
	 */
	public static function make_manifest()
	{
		$files = self::get_file_manifest();
		$configs = array();

		foreach($files as $path => $info){
			if($info['extension'] == 'yml' || $info['extension'] == 'yaml'){
				$configs = array_merge($configs, Symfony\Component\Yaml\Yaml::parse(file_get_contents($path)));
			}
		}
		file_put_contents(TEMP_PATH . DIRECTORY_SEPARATOR .'config_manifest', serialize($configs));
		self::$config_manifest = $configs;

	}

	/**
	 * @return mixed|null
	 */
	public static function get_manifest()
	{
		if(!self::$config_manifest){
			self::$config_manifest = unserialize(file_get_contents(TEMP_PATH . DIRECTORY_SEPARATOR. 'config_manifest'));
		}
		return self::$config_manifest;
	}

	/**
	 * @param $config
	 * @return mixed|null
	 */
	public static function get_config($config)
	{

		$parts = explode('.', $config);
		$val = self::get_manifest();

		foreach($parts as $part){
			if(isset($val[$part])){
				$val = $val[$part];
			}
			else {
				$val = null;
			}
		}

		return $val;
	}


} 