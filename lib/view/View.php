<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 5:20 PM
 * To change this template use File | Settings | File Templates.
 */

use Liquid\Liquid;
use Liquid\Template;

Liquid::set('INCLUDE_SUFFIX', 'tpl');
Liquid::set('INCLUDE_PREFIX', '');

class View extends Object
{

	private $controller;
	private $content;
	private $layout = "";

	public function setController($controller)
	{
		$this->controller = $controller;
	}

	public function getController()
	{
		return $this->controller;
	}

	public function setContents($content)
	{
		$this->content = $content;
	}

	public function getContents()
	{
		return $this->content;
	}

	public function getLayout()
	{
		return $this->layout;
	}

	public function getTemplate($action = ""){
		$templates = array();
		if($action && file_exists(TEMPLATE_PATH . '/' . get_class($this->controller) . '_' . $action . '.tpl')){
			$templates['Main'] = TEMPLATE_PATH . '/' . get_class($this->controller) . '_' . $action . '.tpl';
		}
		else if(file_exists(TEMPLATE_PATH . '/' . get_class($this->controller) . '.tpl')){
			$templates['Main'] = TEMPLATE_PATH . '/' . get_class($this->controller) . '.tpl';
		}
		else if (file_exists(TEMPLATE_PATH . '/Page.tpl')){
			$templates['Main'] = TEMPLATE_PATH . '/Page.tpl';
		}
		else {
			$templates['Main'] = BASE_PATH . '/lib/view/templates/Page.tpl';
		}


		if(file_exists(TEMPLATE_PATH . '/Layout/' . get_class($this->controller) . '_' . $action . '.tpl')){
			$templates['Layout'] = TEMPLATE_PATH . '/Layout/' . get_class($this->controller) . '_' . $action . '.tpl';
		}
		else if(file_exists(TEMPLATE_PATH . '/Layout/' . get_class($this->controller) . '.tpl')){
			$templates['Layout'] = TEMPLATE_PATH . '/Layout/' . get_class($this->controller) . '.tpl';
		}
		else if (file_exists(TEMPLATE_PATH . '/Layout/Page.tpl')){
			$templates['Layout'] = TEMPLATE_PATH . '/Layout/Page.tpl';
		}

		return $templates;
	}

	public function render($action = ""){

		$templates = $this->getTemplate($action);
		
		if(isset($templates['Layout'])){
			$liquid = new Template();
			$liquid->parse(file_get_contents($templates['Layout']));
			$this->layout = $this->processTemplate($liquid);
		}

		$liquid = new Template();
		$liquid->parse(file_get_contents($templates['Main']));
		echo $this->processTemplate($liquid);

	}

	public function processTemplate(Template $template)
	{
		$context = new SS_LiquidContext($this);
		return $template->getRoot()->render($context);
	}

	public function getSettings(){
		return Settings::find_one();
	}


	/**
	 * @return array|bool
	 */
	public function getMenu($parent = 0)
	{
		$parentID = (int)$parent;
		$objects = Page::find("ParentID = {$parentID} AND ShowInMenus = 1");
		return $objects;
	}


	/**
	 * @return bool|string
	 */
	public function getYear()
	{
		return date('Y');
	}

	public function getBase(){
		return Router::get_base();
	}

} 