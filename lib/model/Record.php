<?php
/**
 * Created by Nivanka Fonseka (nivanka@silverstripers.com).
 * User: nivankafonseka
 * Date: 1/22/16
 * Time: 4:32 PM
 * To change this template use File | Settings | File Templates.
 */

class Record extends Object
{

	public function __construct($record = array())
	{
		if($record) {
			foreach($record as $key => $value){
				$this->$key = $value;
			}
		}
	}

	public static function fields()
	{
		return array(
			'ID'			=> 'INT AUTO_INCREMENT PRIMARY KEY',
			'ClassName'		=> 'Varchar(255) NOT NULL',
			'Created'		=> 'TIMESTAMP'
		);
	}


	public function __get($property) {
		if(property_exists($this, $property)){
			return $this->$property;
		}
	}


	public function __set($property, $value) {
		$this->$property = $value;
	}

	public static function make_table($class){

		$return = '<li>';

		$cols = self::get_table_cols($class);


		if(DB::table_exists($class)){
			$sql = self::update_query($cols, $class);
			if($sql){
				$return .= 'Updating ' . $class . '! ... ';
				foreach($sql as $query){
					DB::query($query);
				}
				$return .= ' updated';
			}
		}
		else{
			$return .= 'No Table ' . $class . ' building! ... ';
			$sql = self::create_query($cols, $class);
			if(DB::query($sql) !== false){
				$return .= ' created';
			}
		}
		$return.= '</li>';
		return $return;
	}

	public static function get_table_cols($class){
		$cols = array();

		$classes = ClassManifest::get_ancestry($class);
		if(!empty($classes) && $classes[0] == 'Record'){
			$cols = array_merge($cols, self::fields());
		}
		$cols = array_merge($cols, $class::fields());
		return $cols;
	}


	public static function update_query($cols, $table){
		$table = DB::raw2sql($table);

		$result = DB::query('DESCRIBE `' . $table . '`');
		$currentCols = array();
		while($field = $result->fetch_assoc()){
			$currentCols[$field['Field']] = $field['Type'];
		}

		$queries = array();

		foreach($cols as $col => $type){
			if($col !== 'ID' && array_key_exists($col, $currentCols) && strtolower($type) != strtolower($currentCols[$col])){
				$queries[] = "ALTER TABLE `{$table}` CHANGE `{$col}` `{$col}` " . $type;
			}
			else if (!array_key_exists($col, $currentCols)){
				$queries[] = "ALTER TABLE `{$table}` ADD `{$col}` " . $type;
			}

		}

		if(count($queries)){
			return $queries;
		}
		return false;
	}

	/**
	 * @param $cols
	 * @param $table
	 * @return string
	 */
	public static function create_query($cols, $table)
	{
		$sql = 'CREATE TABLE `' . DB::raw2sql($table) . '` (';
			foreach($cols as $name => $type){
				$sql .= ' `' . DB::raw2sql($name) . '` ' . $type . ',';
			}

			$sql = substr($sql, 0, -1);

		$sql.= ')';
		return $sql;
	}


} 