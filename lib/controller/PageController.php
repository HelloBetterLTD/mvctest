<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/23/16
 * Time: 1:48 PM
 * To change this template use File | Settings | File Templates.
 */

class PageController extends Controller
{

	public function getDefaultRecord(){

		$page = new Page(array(
			'Title'			=> '404',
			'MetaTitle'		=> '404',
			'MenuTitle'		=> '404',
			'Content'		=> 'I am sorry I cant find that page you are asking.'
		));

		return $page;

	}

} 