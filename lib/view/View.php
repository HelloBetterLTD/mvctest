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
	private static $framework_css = false;

	public static function framework_css($include = true)
	{
		self::$framework_css = $include;
	}

	public static function include_framework__css()
	{
		return self::$framework_css;
	}

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
		if($action && file_exists(TEMPLATE_PATH . DIRECTORY_SEPARATOR . get_class($this->controller) . '_' . $action . '.tpl')){
			$templates['Main'] = TEMPLATE_PATH . DIRECTORY_SEPARATOR . get_class($this->controller) . '_' . $action . '.tpl';
		}
		else if(file_exists(TEMPLATE_PATH . DIRECTORY_SEPARATOR . get_class($this->controller) . '.tpl')){
			$templates['Main'] = TEMPLATE_PATH . DIRECTORY_SEPARATOR . get_class($this->controller) . '.tpl';
		}
		else if (file_exists(TEMPLATE_PATH . DIRECTORY_SEPARATOR . 'Page.tpl')){
			$templates['Main'] = TEMPLATE_PATH . DIRECTORY_SEPARATOR . 'Page.tpl';
		}
		else {
			$templates['Main'] = BASE_PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'view' . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'Page.tpl';
		}


		if(file_exists(TEMPLATE_PATH . DIRECTORY_SEPARATOR . 'Layout' . DIRECTORY_SEPARATOR . get_class($this->controller) . '_' . $action . '.tpl')){
			$templates['Layout'] = TEMPLATE_PATH . DIRECTORY_SEPARATOR . 'Layout' . DIRECTORY_SEPARATOR . get_class($this->controller) . '_' . $action . '.tpl';
		}
		else if(file_exists(TEMPLATE_PATH . DIRECTORY_SEPARATOR . 'Layout' . DIRECTORY_SEPARATOR . get_class($this->controller) . '.tpl')){
			$templates['Layout'] = TEMPLATE_PATH . DIRECTORY_SEPARATOR . 'Layout' . DIRECTORY_SEPARATOR . get_class($this->controller) . '.tpl';
		}
		else if (file_exists(TEMPLATE_PATH . DIRECTORY_SEPARATOR . 'Layout' . DIRECTORY_SEPARATOR . 'Page.tpl')){
			$templates['Layout'] = TEMPLATE_PATH . DIRECTORY_SEPARATOR . 'Layout' . DIRECTORY_SEPARATOR . 'Page.tpl';
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
		$html = $this->processTemplate($liquid);

		if(View::include_framework__css() && strpos($html, '</head>') !== false) {
			$html = str_replace('</head>', '<link rel="stylesheet" href="' . Router::get_base() . '/lib/static/framework.css" type="text/css"></head>', $html);
		}

		$logs = array();
		if($errors = Debug::get_error_logs())
		{
			$logs[] = $errors;
		}

		if($debugLogs = Debug::get_logs())
		{
			$logs[] = $debugLogs;
		}

		if(strpos($html, '<body') !== false) {
			$bodyStart = strpos($html, '<body');
			$bodyEnd = strpos($html, '>', $bodyStart);
			$before = substr($html, 0, $bodyEnd + 1);
			$after = substr($html, $bodyEnd + 1);

			$html = $before;
			foreach($logs as $log) {
				$html .= $log;
			}
			$html .= $after;

		}
		else {
			foreach($logs as $log) {
				echo $log;
			}

		}


		echo $html;

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
		$base = Router::get_base();
		if ($base != '/' && substr($base, -1) != '/') {
			$base .= '/';
		}
		return $base;
	}

} 