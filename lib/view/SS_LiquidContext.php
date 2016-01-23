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

		parent::__construct();
	}

	public function get($key)
	{
		$value = "";
		$parts = explode('.', $key);
		foreach($parts as $part){
			if(!empty($this->assigns) && isset($this->assigns[0]) && isset($this->assigns[0][$part])){
				$value = $this->assigns[0][$part];
			}
			else{
				$value = $this->resolveVariable($part, $value);
			}


			if(!$value){
				break;
			}
		}
		return $value;
	}



	public function resolveVariable($key, $lineage = null)
	{
		$value = null;
		$method = 'get' . $key;

		if($lineage && method_exists($lineage, $method)){
			$value = $lineage->$lineage();
		}
		else if ($lineage && is_a($lineage, 'Record') && method_exists($lineage, $key)){
			$value = $lineage->$key();
		}
		else if ($lineage && is_a($lineage, 'Record') && $lineage->field_exists($key)){
			$value = $lineage->$key;
		}
		else if(0 && method_exists($this->viewer, $method)){
			$value = $this->viewer->$method();
		}
		else if(method_exists($this->viewer, $key)){
			$value = $this->viewer->$key();
		}
		else if ($this->controller && method_exists($this->viewer, $method)){
			$value = $this->viewer->$method();
		}
		else if(method_exists($this->record, $key)){
			$value = $this->record->$key();
		}
		else if ($this->record){
			$value = $this->record->$key;
		}
		return $value;
	}



} 