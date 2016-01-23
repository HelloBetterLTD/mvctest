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

	protected $record = array();


	/**
	 * @param array $record
	 */
	public function __construct($record = array())
	{
		if($record) {
			foreach($record as $key => $value){
				$this->$key = $value;
			}
			$this->record = $record;
		}
	}


	/**
	 * @return array
	 */
	public static function fields()
	{
		return array(
			'ID'			=> 'INT AUTO_INCREMENT PRIMARY KEY',
			'ClassName'		=> 'Varchar(255) NOT NULL',
			'Created'		=> 'TIMESTAMP'
		);
	}


	/**
	 * @param $property
	 * @return mixed
	 */
	public function __get($property) {
		if($this->field_exists($this, $property)){
			return $this->$property;
		}
	}


	/**
	 * @param $property
	 * @param $value
	 */
	public function __set($property, $value) {
		$this->$property = $value;
		$this->record[$property] = $value;
	}

	/**
	 * @param $property
	 * @return bool
	 */
	public function field_exists($property)
	{
		return property_exists($this, $property);
	}




	/**
	 * @param $class
	 * @return string
	 */
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


	/**
	 * @param $class
	 * @return array
	 */
	public static function get_table_cols($class){
		$cols = array(
			'ID'		=> 'INT AUTO_INCREMENT PRIMARY KEY'
		);

		$classes = ClassManifest::get_ancestry($class);
		if(!empty($classes) && $classes[0] == 'Record'){
			$cols = array_merge($cols, self::fields());
		}
		$cols = array_merge($cols, $class::fields());
		return $cols;
	}


	/**
	 * @param $cols
	 * @param $table
	 * @return array|bool
	 */
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


	/**
	 * @param string $where
	 * @return bool
	 */
	public static function find_one($where = "")
	{
		$class = get_called_class();
		$select = self::get_select($class);
		$from = self::get_joined_from($class);
		$limit = " LIMIT 1";

		$sql = "SELECT {$select} FROM {$from} ";
		if($where){
			$sql .= " WHERE {$where}";
		}
		$sql .= $limit;

		if($result = DB::query($sql)){
			$record = $result->fetch_assoc();
			$className = trim($record['ClassName']);
			if($className){
				$obj = new $className($record);
				return $obj;
			}
		}
		return false;
	}


	/**
	 * @param string $where
	 * @param string $limit
	 * @param string $order
	 * @return array|bool
	 */
	public static function find($where = "", $limit = "", $order = "")
	{
		$class = get_called_class();
		$select = self::get_select($class);
		$from = self::get_joined_from($class);

		$sql = "SELECT {$select} FROM {$from} ";
		if($where){
			$sql .= " WHERE {$where}";
		}
		if($order){
			$sql .= " ORDER BY {$order}";
		}

		if($limit){
			$sql .= " LIMIT $limit";
		}

		if($result = DB::query($sql)){
			$objects = array();
			while($record = $result->fetch_assoc()){
				$className = $record['ClassName'];
				$objects[] = new $className($record);
			}

			return $objects;
		}

		return false;

	}


	/**
	 * @param $class
	 * @return string
	 */
	public static function get_select($class)
	{
		$ancestry = array_reverse(array_merge(array($class), ClassManifest::get_ancestry($class)), true);

		$cols = array();
		foreach($ancestry as $className){
			if($className !== 'Object' && $className !== 'Record'){
				if($tableCols = self::get_table_cols($className)){
					foreach($tableCols as $col => $type){
						if(!isset($cols[$col])){
							$cols[$col] = "`{$className}`.`{$col}` AS `{$col}`";
						}
					}
				}
			}
		}

		return implode(", ", $cols);
	}


	/**
	 * @param $class
	 * @return string
	 */
	public static function get_joined_from($class)
	{
		$ancestry = array_merge(array($class), ClassManifest::get_ancestry($class));
		$tables = array();
		$on = array();

		$prevClass = "";
		foreach($ancestry as $className){
			if($className !== 'Object' && $className !== 'Record'){
				$tables[] = $className;
				if($prevClass){
					$on[] = "`{$prevClass}`.`ID` = `{$className}`.`ID`";
				}
				$prevClass = $className;
			}
		}

		$strRet = "";
		if(!empty($tables)){
			for($i = 0; $i < count($tables); $i++){
				if(empty($strRet)){
					$strRet .= $tables[$i];
				}
				else {
					$strRet .= " LEFT JOIN " . $tables[$i];
					if(isset($on[$i - 1])){
						$strRet .= " ON " . $on[$i - 1];
					}
				}
			}
		}

		return $strRet;

	}


	/**
	 * @return $this
	 */
	public function toLiquid()
	{
		return $this;
	}


	/**
	 * @return array
	 */
	public function toArray()
	{
		return $this->record;
	}


} 