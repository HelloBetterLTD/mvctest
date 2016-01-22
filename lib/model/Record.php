<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 4:32 PM
 * To change this template use File | Settings | File Templates.
 */

class Record extends Object
{

	public static function fields()
	{
		return array(
			'ID'			=> 'Int',
			'ClassName'		=> 'Varchar(255)',
			'Created'		=> 'DateTime'
		);
	}


	public function __get($property) {
		return $this->$property;
	}


	public function __set($property, $value) {
		$this->$property = $value;
	}

} 