<?php

class Varchar extends DBField 
{

	public function __construct($length = 255)
	{
		$this->length = $length;
	}

	public function SQLFieldType()
	{
		return 'VARCHAR(' . $this->length . ')'
	}


}