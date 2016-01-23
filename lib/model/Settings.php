<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/23/16
 * Time: 1:44 PM
 * To change this template use File | Settings | File Templates.
 */

class Settings extends Record
{

	public static function fields(){
		return array(
			'SiteTitle'			=> 'Varchar(255)',
			'SidebarTitle'		=> 'Varchar(50)',
			'SidebarContent'	=> 'Text'
		);
	}

} 