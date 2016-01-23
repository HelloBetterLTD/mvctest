<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 4:32 PM
 * To change this template use File | Settings | File Templates.
 */

class Page extends Record
{

	public static function fields()
	{
		return array(
			'Title'				=> 'Varchar(255)',
			'URLSegment'		=> 'Varchar(255)',
			'MetaTitle'			=> 'Varchar(255)',
			'MenuTitle'			=> 'Varchar(255)',
			'Content'			=> 'Text',
			'ParentID'			=> 'Int',
		);
	}


	public function Link()
	{
		return $this->URLSegment;
	}


	public function getController()
	{
		$controllerClass = $this->ClassName . 'Controller';
		if(ClassManifest::has_class($controllerClass)){
			$controller = new $controllerClass();
		}
		else {
			$controller = new PageController();
		}
		$controller->setRecord($this);
		return $controller;
	}


} 