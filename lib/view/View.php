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

		$arrTemplates = $this->getTemplate();

		if(isset($arrTemplates['Layout'])){

		}

		echo '<pre>' . print_r($arrTemplates, 1) . '</pre>';



		// $liquid = new Template($protectedPath . 'templates' . DIRECTORY_SEPARATOR);



	}

} 