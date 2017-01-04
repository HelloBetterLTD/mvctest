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
			'ShowInMenus'		=> 'Boolean',
			'MetaTitle'			=> 'Varchar(255)',
			'MenuTitle'			=> 'Varchar(255)',
			'Content'			=> 'Text',
			'ParentID'			=> 'Int',
		);
	}
	
	public function requireDefaultRecords()
	{
		if(!Page::find_one("URLSegment = 'home'")) {
			$homePage = new Page(array(
				'Title'				=> 'Welcome to SilverStripers MVC Test',
				'URLSegment'		=> 'home',
				'ShowInMenus'		=> '1',
				'MetaTitle'			=> 'Home Page | SilverStripers PVT. LTD',
				'MenuTitle'			=> 'Home',
				'Content'			=> '<p>You can edit this content by changing the data records in the CMS.</p>'
			));
			$homePage->write();
		}
		
		if(!Page::find_one("URLSegment = 'about-us'")) {
			$homePage = new Page(array(
				'Title'				=> 'About us',
				'URLSegment'		=> 'about-us',
				'ShowInMenus'		=> '1',
				'MetaTitle'			=> 'About | SilverStripers PVT. LTD',
				'MenuTitle'			=> 'About',
				'Content'			=> '<p>You can edit this content by changing the data records in the CMS.</p>'
			));
			$homePage->write();
		}
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