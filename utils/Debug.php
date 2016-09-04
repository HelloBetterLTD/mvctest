<?php

/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 9/4/16
 * Time: 6:08 AM
 * To change this template use File | Settings | File Templates.
 */
class Debug extends Object
{

	private static $logs = array();
	private static $errors = array();

	/**
	 * @param $message
	 * @param null $var
	 */
	public static function log($message, $var = null)
	{

		if(isset($_REQUEST['debug'])) {
			View::framework_css();
			self::$logs[] = "<p class='ss_debug'>{$message}</p>";
			if($var){
				self::$logs[] = "<div class='ss_debug__var'><pre>". print_r($var, 1) ."</pre></div>";
			}
		}

	}

	public static function get_logs()
	{
		if(count(self::$logs))
		{
			return implode("\n", self::$logs);
		}
	}

	public static function log_error($log, $bt)
	{
		self::$errors[] = "<div class='ss_error'><div class='ss_error__inner'>"
			. $log
			. "<pre>"
			. Debug::display_filter_backtrace(debug_backtrace())
			. "</pre>"
			."</div></div>";
	}

	public static function get_error_logs()
	{
		if(count(self::$errors))
		{
			return implode("\n", self::$errors);
		}
	}

	public static function filter_backtrace($bt)
	{
		$filtered = array();
		foreach($bt as $i => $frame) {
			if(isset($bt[$i]['file'])) {
				$line = "";
				if(isset($bt[$i]['function']) && isset($bt[$i]['class']) && isset($bt[$i]['type'])) {
					$line .= $bt[$i]['class'] . $bt[$i]['type'] . $bt[$i]['function'] . "<br>";
				}
				$line .= $bt[$i]['file'] . " on Line <strong>" . $bt[$i]['line'] . "</strong>";
				$filtered[] = $line;
			}
		}

		return array_reverse($filtered);


	}

	public static function display_filter_backtrace($bt)
	{
		echo "<ul class='ss_error--bt'><li>";
			echo implode("</li><li>", self::filter_backtrace($bt));
		echo "</li></ul>";
	}



}