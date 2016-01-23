<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/23/16
 * Time: 8:48 PM
 * To change this template use File | Settings | File Templates.
 */

class Member extends Record
{

	public static function fields()
	{
		return array(
			'FirstName'			=> 'Varchar(255)',
			'Surname'			=> 'Varchar(255)',
			'Email'				=> 'Varchar(255)',
		);
	}

} 