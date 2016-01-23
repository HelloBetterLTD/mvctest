<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 4:31 PM
 * To change this template use File | Settings | File Templates.
 */

class Controller extends Object
{

	private $record = null;

	public function setRecord($record){
		$this->record = $record;
	}

	public function getRecord(){
		return $this->record;
	}


	public function index(){
		return;
	}

} 