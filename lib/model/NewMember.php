<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/23/16
 * Time: 8:48 PM
 * To change this template use File | Settings | File Templates.
 */

class NewMember extends Member
{

	public static function fields()
	{
		return array(
			'Address1'			=> 'Varchar(255)',
			'Address2'			=> 'Varchar(255)',
		);
	}

} 