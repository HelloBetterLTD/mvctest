<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 6:10 PM
 * To change this template use File | Settings | File Templates.
 */

class SS_LiquidContext extends \Liquid\Context
{

	private $viewer;
	private $controller;
	private $record;

	public function __construct(View $viewer) {
		$this->viewer = $viewer;
		$this->controller = $viewer->getController();
		if($this->controller){
			$this->record = $this->controller->getRecord();
		}
	}

	public function get($key){
		$value = "";

		$method = 'get' . $key;

		if(method_exists($this->viewer, $method)){
			$value = $this->viewer->$method();
		}
		else if ($this->controller && method_exists($this->viewer, $method)){
			$value = $this->viewer->$method();
		}
		else if ($this->record){
			$value = $this->record->$key;
		}

		return $value;
	}

} 