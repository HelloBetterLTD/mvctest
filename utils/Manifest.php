<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 2:12 PM
 * To change this template use File | Settings | File Templates.
 */

class Manifest extends Object
{

	protected static $file_manifest = null;

	public static function make_manifest()
	{
		if(isset($_GET['build'])){
			return true;
		}
		return (count(scandir(TEMP_PATH)) == 2);
	}

	public static function reload_manifest()
	{
		self::build_file_manifest(BASE_PATH);
		ClassManifest::make_manifest();
		ConfigManifest::make_manifest();
	}

	public static function build_file_manifest($base)
	{
		$files = self::find_files($base);
		file_put_contents(TEMP_PATH . '/file_manifest', serialize($files));
		self::$file_manifest = $files;
	}

	public static function get_file_manifest()
	{
		if(self::$file_manifest){
			return self::$file_manifest;
		}
		return unserialize(file_get_contents(TEMP_PATH . '/file_manifest'));
	}


	public static function find_files($base)
	{
		$files = array();
		foreach(scandir($base) as $file){
			$path = $base . '/' . $file;

			if($file == '.' || $file == '..' || $file == 'vendor'){
				continue;
			}

			if(is_dir($path)){
				$files = array_merge($files, self::find_files($path));
			}
			else if(self::accept_file($path)){
				$files[$path] = array(
					'extension'			=> pathinfo($path, PATHINFO_EXTENSION)
				);
			}
		}
		return $files;
	}

	public static function accept_file($path)
	{
		if($path == '.' || $path == '..'){
			return false;
		}

		$ext = pathinfo($path, PATHINFO_EXTENSION);

		return in_array($ext, array(
			'php',
			'php5',
			'tpl',
			'yml',
			'yaml'
		));


	}

} 