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
		$dbFile = BASE_PATH . DIRECTORY_SEPARATOR . 'db.php';
		if(file_exists($dbFile)) {
			$db_configs = @include(BASE_PATH . DIRECTORY_SEPARATOR . 'db.php');
			if ($db_configs && is_array($db_configs)) {
				$host = $db_configs['host'];
				$user = $db_configs['username'];
				$pass = $db_configs['password'];
				$database = $db_configs['database'];

				if ($host && $user && $database) {
					self::$conn = new mysqli($host, $user, $pass, $database);
				} else {
					user_error('I can\'t find any database records.', E_USER_ERROR);
					exit;
				}
			} else {
				user_error('I can\'t find any database records.', E_USER_ERROR);
				exit;
			}
		}
		else {
			user_error('I can\'t find any database configs file.', E_USER_ERROR);
			exit;
		}




	}

	public static function query($sql, $skipError = false)
	{
		$result = self::$conn->query($sql);
		if (self::$conn->error && !$skipError) {
			user_error(sprintf("DB Error: %s,<br> %s\n", self::$conn->error, $sql), E_USER_ERROR);
		}
		return $result;
	}

	public static function raw2sql($string)
	{
		return self::$conn->real_escape_string($string);
	}

	public static function table_exists($table)
	{
		$tables = self::query("SELECT 1 FROM `" . self::raw2sql($table) . "` LIMIT 1", true);
		return $tables !== false;
	}

	public static function last_insert_id()
	{
		return self::$conn->insert_id;
	}


} 