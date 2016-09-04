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

	/**
	 * @param $message
	 * @param null $var
	 */
	public static function log($message, $var = null)
	{

		if(isset($_REQUEST['debug'])) {
			echo "<p class='debug' style='background: #f1f1f1; border: 1px solid #cccccc; margin: 20px; padding: 8px 16px; font-size: 12px;'>{$message}</p>";
			if($var){
				echo "<div class='debug__var' style='background: #f1f1f1; border: 1px solid #cccccc; margin: 20px; padding: 8px 16px; font-size: 12px;'><pre>". print_r($var, 1) ."</pre></div>";
			}
		}

	}



}