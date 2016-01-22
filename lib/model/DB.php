<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 4:31 PM
 * To change this template use File | Settings | File Templates.
 */

class DB extends Object
{

	private static $conn = null;

	public static function init()
	{

		$host = ConfigManifest::get_config('Database.host');
		$user = ConfigManifest::get_config('Database.user');
		$pass = ConfigManifest::get_config('Database.password');
		$database = ConfigManifest::get_config('Database.db');

		if($host && $user && $pass && $database){
			self::$conn = new mysqli($host, $user, $pass, $database);
		}
		else {
			user_error('I can\'t find any database records', E_USER_WARNING);
		}

	}

	public static function query($sql){
		$result = self::$conn->query($sql);
		if (self::$conn->connect_errno) {
			user_error("Connect failed: %s\n", self::$conn->connect_error);
		}
		return $result;
	}

	public static function raw2sql($string){
		return self::$conn->real_escape_string($string);
	}

	public static function table_exists($table){
		$tables = self::query("SELECT 1 FROM `" . self::raw2sql($table) . "` LIMIT 1");
		return $tables !== false;
	}


} 