<?php

abstract class DBField extends Object {

	private $length = 0;
	protected $value = null;
	
	abstract function SQLFieldType();

	public function setValue($value)
	{
		return $this->value = $value;
	}
	
	public function getValue()
	{
		return $this->value;
	}
	
	public function validate()
	{
		return true;
	}
	
}