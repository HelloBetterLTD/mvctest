<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/23/16
 * Time: 8:33 PM
 * To change this template use File | Settings | File Templates.
 */

class ErrorController extends Controller
{

	public function getDefaultRecord(){

		$page = new Page(array(
			'Title'			=> $this->getTitle(),
			'MetaTitle'		=> $this->getTitle(),
			'MenuTitle'		=> $this->getTitle(),
			'Content'		=> $this->getContent()
		));

		return $page;

	}

	public function getErrorCode()
	{
		return 200;
	}

	public function getTitle(){
		return 'Error Page';
	}

	public function getContent(){
		return '<p>An error has occurred while processing your request.</p>';
	}

} 