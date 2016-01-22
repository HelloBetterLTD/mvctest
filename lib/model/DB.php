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



} 