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
			'ShowInMenus'		=> 'Int(1)',
			'MetaTitle'			=> 'Varchar(255)',
			'MenuTitle'			=> 'Varchar(255)',
			'Content'			=> 'Text',
			'ParentID'			=> 'Int',
		);
	}


	public function Link($action = "")
	{
		if(empty($this->ParentID) || $this->ParentID == 0) {
			return $this->URLSegment . "/" . $action;
		}
		if($parent = $this->getParent()){
			return $parent->Link() . $this->URLSegment . "/" . $action;
		}

	}

	public function getParent()
	{
		$parents = Page::find("ID = " . (int)$this->ParentID);
		if($parents){
			return $parents[0];
		}
		return null;
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


	public function getChildren()
	{
		return Page::find("ParentID = " . (int)$this->ID . " AND ShowInMenus = 1");
	}


} 