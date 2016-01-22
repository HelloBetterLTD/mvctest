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

	public function setContents($content)
	{
		$this->content = $content;
	}

	public function getTemplate(){
		$templates = array();
		if(file_exists(TEMPLATE_PATH . '/' . get_class($this->controller) . '.tpl')){
			$templates['Main'] = TEMPLATE_PATH . '/' . get_class($this->controller) . '.tpl';
		}
		else if (file_exists(TEMPLATE_PATH . '/Page.tpl')){
			$templates['Main'] = TEMPLATE_PATH . '/Page.tpl';
		}
		else {
			$templates['Main'] = BASE_PATH . '/lib/view/templates/Page.tpl';
		}


		if(file_exists(TEMPLATE_PATH . '/Layout' . get_class($this->controller) . '.tpl')){
			$templates['Layout'] = TEMPLATE_PATH . '/Layout' . get_class($this->controller) . '.tpl';
		}
		else if (file_exists(TEMPLATE_PATH . '/Layout/Page.tpl')){
			$templates['Layout'] = TEMPLATE_PATH . '/Layout/Page.tpl';
		}

		return $templates;
	}

	public function render(){

		$templates = $this->getTemplate();

		if(isset($templates['Layout'])){
			$liquid = new Template();
			$liquid->parse(file_get_contents($templates['Layout']));
			$this->layout = $liquid->render();
		}

		$liquid = new Template();
		$liquid->parse(file_get_contents($templates['Main']));
		echo $liquid->render();

	}

} 